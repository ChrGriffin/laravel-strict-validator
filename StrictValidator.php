<?php

namespace ChrGriffin;

use Illuminate\Support\Facades\Validator;

class StrictValidator
{
    /**
     * Data to validate.
     *
     * @var array
     */
    protected $data = [];

    /**
     * Structure to validate the data against.
     *
     * @var array
     */
    protected $structure = [];

    /**
     * StrictValidator constructor.
     *
     * @param array $data Data to validate.
     * @param array $structure Structure to validate the data against.
     * @return void
     */
    public function __construct(array $data, array $structure)
    {
        $this->data = $data;
        $this->structure = $structure;
    }

    /**
     * Validate all the given data against the given structure.
     *
     * @return bool
     */
    public function validate() : bool
    {
        $validator = Validator::make(
            $this->data, $this->structure
        );

        if($validator->fails()) {
            return false;
        }

        return $this->validateArray($this->data, $this->structure);
    }

    /**
     * Validate a specific given array against the structure for that specific array.
     *
     * @param array $data
     * @param array $structure
     * @return bool
     */
    protected function validateArray(array $data, array $structure) : bool
    {
        // the array can't contain any values we're not expecting
        if(!empty(array_diff_key($data, $structure))) {
            return false;
        }

        // recursively perform the same validation on any nested arrays
        foreach($data as $dataIndex => $dataValue) {
            if(is_array($dataValue) && $this->_isAssociative($dataValue)) {
                $nestedStructure = [];
                $regex = '/^' . $dataIndex . '.(\w+)/';

                foreach($structure as $structureIndex => $structureValue) {
                    if(preg_match($regex, $structureIndex)) {
                        $newIndex = preg_replace($regex, '$1', $structureIndex);
                        $nestedStructure[$newIndex] = $structureValue;
                    }
                }

                if(!$this->validateArray($dataValue, $nestedStructure)) {
                    return false;
                }
            }
        }

        return true;
    }

    /**
     * Determines whether a given array is an associative array or not.
     *
     * @param array $array
     * @return bool
     */
    private function _isAssociative(array $array) : bool
    {
        if([] === $array) {
            return false;
        }
        return array_keys($array) !== range(0, count($array) - 1);
    }
}