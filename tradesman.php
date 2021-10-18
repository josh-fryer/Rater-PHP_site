<?php
    require_once ('user.php');
    class Tradesman extends User
    {
        private  $availableFrom;
        private  $availableTo;

        private  $hourlyRate;
        private  $companyName;
        private  $addressLine1;
        private  $addressLine2;
        private  $postcode;
        private  $city;

        private  $trade;
        private  $averageRating;
        private  $reviewPin;         
        private  $proReg;
        
        public function setAvailableTo($availableTo)
        {
            $this->availableTo = $availableTo;
        }

        public function getAvailableTo()
        {
            return $this->availableTo;
        }

        public function setAvailableFrom($availableFrom)
        {
            $this->availableFrom = $availableFrom;
        }

        public function getAvailableFrom()
        {
            return $this->availableFrom;
        }

        public function setHourlyRate($hourlyRate)
        {
            $this->hourlyRate = $hourlyRate;
        }

        public function getHourlyRate()
        {
            return $this->hourlyRate;
        }

        public function setProReg($proReg)
        {
            $this->proReg = $proReg;
        }

        public function getProReg()
        {
            return $this->proReg;
        }

        public function setTrade($trade)
        {
            $this->trade = $trade;
        }

        public function getTrade()
        {
            return $this->trade;
        }

        public function setReviewPin($reviewPin)
        {
            $this->reviewPin = $reviewPin;
        }

        public function getreviewPin()
        {
            return $this->reviewPin;
        }

        public function setAverageRating($avgRt)
        {
            $this->averageRating = $avgRt;
        }

        public function getAverageRating()
        {
            return $this->averageRating;
        }

        public function setAddressLine1($addressLine1)
        {
            $this->addressLine1 = $addressLine1;
        }

        public function getAddressLine1()
        {
            return $this->addressLine1;
        }

        public function setAddressLine2($addressLine2)
        {
            $this->addressLine2 = $addressLine2;
        }

        public function getAddressLine2()
        {
            return $this->addressLine2;
        }

        public function setCity($city)
        {
            $this->city = $city;
        }

        public function getCity()
        {
            return $this->city;
        }

        public function setPostcode($pc)
        {
            $this->postcode = $pc;
        }

        public function getPostcode()
        {
            return $this->postcode;
        }

        public function setCompanyName($companyName)
        {
            $this->companyName = $companyName;
        }

        public function getCompanyName()
        {
            return $this->companyName;
        }
    }
?>
