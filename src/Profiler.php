<?php

namespace Aurora;

use Aurora\Profiler\Exception\MissingPointException;

class Profiler
{
   public $points = [];

   public function start($point, $time = true, $memory = true)
   {
      if (!($time || $memory)) {
         throw new MissingPointDataException("Point ${point} has no data to work with, time or memory should be present");
      }
      $data = [];

      if ($time) {
         $data["start"]["time"] = microtime();
      }
      if ($memory) {
         $data["start"]["memory"] = memory_get_usage();
      }

      return $this->points[$point] = $data;
   }

   public function end($point)
   {
      if (!isset($this->points[$point])) {
         throw new PointNotFoundException("Point ${point} wasn't found");
      }

      if (isset($this->points[$point]["start"]["time"])) {
         $data["end"]["time"] = microtime();
      } else {
         $data["end"]["memory"] = memory_get_usage();
      }

      $this->points[$point] = $data;
   }

   public function difference($point, $secondPoint = null)
   {
      if (!isset($this->points[$point])) {
         throw new PointNotFoundException("Point ${point} wasn't found");
      }

      if ($secondPoint === null) {
         $secondPoint = $point;
      }

      if (isset($this->points[$point]["start"]["time"])
         && isset($this->points[$secondPoint]["start"]["time"])
      ) {
         $difference["time"] = $this->points[$point]["start"]["time"] - $this->points[$secondPoint]["start"]["time"];
      } else {
         $difference["time"] = $this->points[$point]["start"]["memory"] - $this->points[$secondPoint]["start"]["memory"];
      }

      return $difference;
   }
}
