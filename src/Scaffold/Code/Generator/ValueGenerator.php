<?php
/**
 * @author Evgeny Shpilevsky <evgeny@shpilevsky.com>
 */

namespace Scaffold\Code\Generator;

use Zend\Code\Generator\ValueGenerator as ZendValueGenerator;
use Zend\Code\Generator\Exception;

class ValueGenerator extends ZendValueGenerator
{


    /**
     * @throws Exception\RuntimeException
     * @return string
     */
    public function generate()
    {
        $type = $this->type;

        if ($type != self::TYPE_AUTO) {
            $type = $this->getValidatedType($type);
        }

        $value = $this->value;

        if ($type == self::TYPE_AUTO) {
            $type = $this->getAutoDeterminedType($value);

            if ($type == self::TYPE_ARRAY) {
                $rii = new \RecursiveIteratorIterator(
                    $it = new \RecursiveArrayIterator($value),
                    \RecursiveIteratorIterator::SELF_FIRST
                );
                foreach ($rii as $curKey => $curValue) {
                    if (!$curValue instanceof ValueGenerator) {
                        $curValue
                            = new self($curValue, self::TYPE_AUTO, self::OUTPUT_MULTIPLE_LINE, $this->getConstants());
                        $rii->getSubIterator()->offsetSet($curKey, $curValue);
                    }
                    $curValue->setArrayDepth($rii->getDepth());
                }
                $value = $rii->getSubIterator()->getArrayCopy();
            }

        }

        $output = '';

        switch ($type) {
            case self::TYPE_BOOLEAN:
            case self::TYPE_BOOL:
                $output .= ($value ? 'true' : 'false');
                break;
            case self::TYPE_STRING:
                $output .= self::escape($value);
                break;
            case self::TYPE_NULL:
                $output .= 'null';
                break;
            case self::TYPE_NUMBER:
            case self::TYPE_INTEGER:
            case self::TYPE_INT:
            case self::TYPE_FLOAT:
            case self::TYPE_DOUBLE:
            case self::TYPE_CONSTANT:
                $output .= $value;
                break;
            case self::TYPE_ARRAY:
                $output .= 'array(';
                $curArrayMultiblock = true;
                if ($this->outputMode == self::OUTPUT_MULTIPLE_LINE) {
                    $output .= self::LINE_FEED . str_repeat($this->indentation, $this->arrayDepth + 1);
                }
                $outputParts = array();
                $noKeyIndex = 0;
                foreach ($value as $n => $v) {
                    /* @var $v ValueGenerator */
                    $v->setArrayDepth($this->arrayDepth + 1);
                    $partV = $v->generate();
                    $short = false;
                    if (is_int($n)) {
                        if ($n === $noKeyIndex) {
                            $short = true;
                            $noKeyIndex++;
                        } else {
                            $noKeyIndex = max($n + 1, $noKeyIndex);
                        }
                    }

                    if ($short) {
                        $outputParts[] = $partV;
                    } else {
                        $outputParts[] = (is_int($n) ? $n : self::escape($n)) . ' => ' . $partV;
                    }
                }
                $padding = ($this->outputMode == self::OUTPUT_MULTIPLE_LINE)
                    ? self::LINE_FEED . str_repeat($this->indentation, $this->arrayDepth + 1)
                    : ' ';
                $output .= implode(',' . $padding, $outputParts);
                if ($curArrayMultiblock == true && $this->outputMode == self::OUTPUT_MULTIPLE_LINE) {
                    $output .= self::LINE_FEED . str_repeat($this->indentation, $this->arrayDepth);
                }
                $output .= ')';
                break;
            case self::TYPE_OTHER:
            default:
                throw new Exception\RuntimeException(sprintf(
                    'Type "%s" is unknown or cannot be used as property default value.',
                    get_class($value)
                ));
        }

        return $output;
    }

}