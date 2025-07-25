<?php

/**
 * Class AddressValidation
 *
 * Provides validation and sanitization utilities for address-related data.
 * Implements the singleton pattern to ensure a single instance is used throughout the application.
 * Extends the abstract Validation class (not shown here).
 *
 * Usage:
 * - Use AddressValidation::getValidator() to obtain the singleton instance.
 * - Use instance methods to validate individual address fields.
 * - Use AddressValidation::sanitizeData(&$data) to trim whitespace from address fields in an associative array.
 *
 * Methods:
 * - static getValidator(): Returns the singleton instance of AddressValidation.
 * - static sanitizeData(array &$data): Trims whitespace from common address fields in the provided data array.
 * - validateHouseNo(int|string $param): Validates that the house number contains only allowed characters (letters, numbers, #, -).
 * - validateStreet(string $param): Validates that the street contains only allowed characters (letters, numbers, spaces, apostrophes, hyphens).
 * - validateCity(string $param): Validates that the city contains only allowed characters (letters, spaces, apostrophes, hyphens).
 * - validateRegion(string $param): Validates that the region contains only allowed characters (letters, numbers, spaces, hyphens).
 * - validatePostalCode(string $param): Validates the postal code format (currently checks for digits and optional hyphen).
 *
 * Each validation method returns an array with a 'status' key (boolean) and, if invalid, a 'message' key describing the error.
 */

class AddressValidation extends Validation
{
    private static $addressValidation;

    private function __construct() {}
    public static function getValidator(): AddressValidation
    {
        if (!isset(self::$addressValidation))
            self::$addressValidation = new self();

        return self::$addressValidation;
    }
    public static function sanitizeData(array &$data): void
    {
        self::sanitize($data, ['houseNo', 'street', 'city', 'region', 'postalCode', 'country']);
    }

    public function validateHouseNo(int|String $param): array
    {
        if (preg_match('/[^\w#-]/', $param)) {
            return [
                'status' => false,
                'message' => 'House number can only contain letters, numbers, hash symbol(#), and hyphens(-).'
            ];
        }

        return ['status' => true];
    }

    public function validateStreet(String $param): array
    {
        if (preg_match('/[^\w\s\'\-]/', $param)) {
            return [
                'status' => false,
                'message' => 'Street can only contain letters, numbers, spaces, apostrophe(\'), and hyphens(-).'
            ];
        }

        return ['status' => true];
    }

    public function validateCity(String $param): array
    {
        if (preg_match('/[^a-zA-Z\s\'\-]/', $param)) {
            return [
                'status' => false,
                'message' => 'City can only contain letters, spaces, apostrophe(\'), and hyphens(-).'
            ];
        }
        return ['status' => true];
    }

    public function validateRegion(String $param): array
    {
        if (preg_match('/[^\w\s\-]/', $param)) {
            return [
                'status' => false,
                'message' => 'Region can only contain letters, numbers, spaces, and hyphens(-).'
            ];
        }
        return ['status' => true];
    }

    public function validatePostalCode(String $param): array
    {
        if (preg_match('/[^(\d{4,})(\-\d+)?]/', $param)) {
            return [
                'status' => false,
                'message' => 'Invalid Postal code format.'
            ];
        }
        return ['status' => true];
    }
}
