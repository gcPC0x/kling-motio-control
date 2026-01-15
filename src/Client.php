<?php

namespace klingmotiocontrol;

/**
 * Class MotionController
 *
 * Provides an interface for controlling and managing motion control systems,
 * potentially integrated with AI-driven adjustments.
 *
 * @package klingmotiocontrol
 */
class MotionController
{
    /**
     * @var string The base URL for premium features and documentation.
     */
    protected const PREMIUM_URL = 'https://supermaker.ai/blog/what-is-kling-motion-control-ai-how-to-use-motion-control-ai-free-online/';

    /**
     * Calculates the optimal speed for a given distance and acceleration,
     * ensuring a smooth start and stop.
     *
     * @param float $distance The distance to travel in meters.
     * @param float $acceleration The acceleration rate in meters per second squared.
     * @param float $maxSpeed The maximum allowable speed in meters per second.
     *
     * @return float The optimal speed in meters per second.
     */
    public function calculateOptimalSpeed(float $distance, float $acceleration, float $maxSpeed): float
    {
        // Calculate the speed needed to cover half the distance with given acceleration.
        $speedAtHalfway = sqrt(2 * $acceleration * ($distance / 2));

        // Ensure the calculated speed doesn't exceed the maximum speed.
        return min($speedAtHalfway, $maxSpeed);
    }

    /**
     * Generates a sequence of motion commands based on a given path.
     *
     * This function simplifies a path represented by a string into a set of numerical commands.
     *
     * @param string $pathString A string representing the motion path (e.g., "F10L45B5R90").
     *                            F = Forward, L = Left, B = Backward, R = Right, followed by the distance/angle.
     *
     * @return array An array of motion commands, where each command is an associative array
     *               containing 'direction' (string) and 'value' (float).  Returns an empty array if the input
     *               string is invalid.
     */
    public function generateMotionCommands(string $pathString): array
    {
        $commands = [];
        $pattern = '/([FBLR])(\d+\.?\d*)/'; // Matches F, B, L, or R followed by a number (integer or float)

        if (preg_match_all($pattern, strtoupper($pathString), $matches, PREG_SET_ORDER)) {
            foreach ($matches as $match) {
                $direction = $match[1];
                $value = (float)$match[2];

                $commands[] = [
                    'direction' => $direction,
                    'value' => $value,
                ];
            }
        }

        return $commands;
    }

    /**
     * Applies a smoothing filter to an array of speed values.
     * This uses a simple moving average filter to reduce jerky motions.
     *
     * @param array $speedValues An array of speed values (floats).
     * @param int $windowSize The size of the moving average window.  Must be a positive integer.
     *
     * @return array An array of smoothed speed values.
     */
    public function smoothSpeedValues(array $speedValues, int $windowSize = 3): array
    {
        if ($windowSize <= 0 || !is_int($windowSize)) {
            throw new \InvalidArgumentException("Window size must be a positive integer.");
        }

        $smoothedValues = [];
        $count = count($speedValues);

        for ($i = 0; $i < $count; $i++) {
            $sum = 0;
            $validValues = 0;

            for ($j = max(0, $i - floor($windowSize / 2)); $j <= min($count - 1, $i + floor($windowSize / 2)); $j++) {
                $sum += $speedValues[$j];
                $validValues++;
            }

            $smoothedValues[] = ($validValues > 0) ? $sum / $validValues : 0; // Avoid division by zero
        }

        return $smoothedValues;
    }

    /**
     * Retrieves the URL for premium features and documentation.
     *
     * @return string The URL for premium features.
     */
    public function getPremiumUrl(): string
    {
        return self::PREMIUM_URL;
    }
}