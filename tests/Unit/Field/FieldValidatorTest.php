<?php

use Assely\Field\FieldValidator;

class FieldValidatorTest extends TestCase
{
    /**
     * @test
     */
    public function accepting_rules_on_construction()
    {
        $rules = $this->getFieldValidator(['min:3', 'max:6'])->getRules();

        $this->assertEquals(['min:3', 'max:6'], $rules);
    }

    /**
     * @test
     */
    public function ability_to_return_stored_rules()
    {
        $validator = $this->getFieldValidator();

        $validator->setRules(['required', 'min:3']);

        $this->assertEquals(['required', 'min:3'], $validator->getRules());
    }

    /**
     * @test
     */
    public function attributes_mapping_from_rules()
    {
        $validator = $this->getFieldValidator();

        $validator->setRules(['required', 'min:3']);

        $this->assertEquals([
            'required' => 'required',
            'min' => '3',
        ], $validator->getAttributes());
    }

    /**
     * @test
     */
    public function validator_json_sanitization()
    {
        $validator = $this->getFieldValidator()->setRules(['required', 'min:3']);

        $this->assertEquals([
            'rules' => ['required', 'min:3'],
            'attributes' => [
                'required' => 'required',
                'min' => '3',
            ],
        ], $validator->jsonSerialize());

        $this->assertEquals(json_encode([
            'rules' => ['required', 'min:3'],
            'attributes' => [
                'required' => 'required',
                'min' => '3',
            ],
        ]), json_encode($validator));
    }

    protected function getFieldValidator(array $rules = [])
    {
        return new FieldValidator($rules);
    }
}
