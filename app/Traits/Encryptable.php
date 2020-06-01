<?php

namespace App\Traits;

use Crypt;
use Illuminate\Support\Facades\Log;
use Illuminate\Contracts\Encryption\DecryptException;

trait Encryptable
{
        public function attributesToArray()
        {
            try {
                $attributes = parent::attributesToArray();
                foreach($this->getEncrypts() as $key) {
                    if(array_key_exists($key, $attributes)) {
                        $attributes[$key] = decrypt($attributes[$key]);
                    }
                }
                return $attributes;
            } catch (DecryptException $e) {}
        }

        public function getAttributeValue($key)
        {
            try {
                if(in_array($key, $this->getEncrypts())) {
                    return decrypt($this->attributes[$key]);
                }
                return parent::getAttributeValue($key);
            } catch (DecryptException $e) {}
        }

        public function setAttribute($key, $value)
        {
            try {
                if(in_array($key, $this->getEncrypts())) {
                    $this->attributes[$key] = encrypt($value);
                }else{
                    parent::setAttribute($key, $value);
                }
                return $this;
            } catch (DecryptException $e) {}
        }

        protected function getEncrypts()
        {
            try {
                return property_exists($this, 'encrypts') ? $this->encrypts : [];
            } catch (DecryptException $e) {}
        }
}