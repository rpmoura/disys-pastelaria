<?php

namespace Rules;

use App\Rules\ImageBase64;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Concerns\ValidatesAttributes;
use Tests\Fixture\ImageFixture;
use Tests\TestCase;

class ImageBase64Test extends TestCase
{
    use ValidatesAttributes;

    /**
     * @test
     */
    public function shouldValidateImageBase64()
    {
        $validator = Validator::make(['image' => ImageFixture::getImageBase64Encoded()], ['image' => new ImageBase64()]);

        $this->assertTrue($validator->passes());
    }

    /**
     * @test
     */
    public function shouldInvalidStringBase64Value()
    {
        $validator = Validator::make(['image' => 'invalid_string_base64'], ['image' => new ImageBase64()]);

        $this->assertFalse($validator->passes());
        $this->assertEquals(__('validation.base64string', ['attribute' => 'image']), $validator->getMessageBag()->first());
    }

    /**
     * @test
     */
    public function shouldInvalidMimeTypeFromStringBase64()
    {
        $value = 'data:text/plain;base64,dGVzdCBiYXNlNjQgc3RyaW5nLiBpcyBub3QgaW1hZ2UgbWltZXR5cGU=';

        $validator = Validator::make(['image' => $value], ['image' => new ImageBase64()]);

        $this->assertFalse($validator->passes());
        $this->assertEquals(__('validation.image', ['attribute' => 'image']), $validator->getMessageBag()->first());
    }
}
