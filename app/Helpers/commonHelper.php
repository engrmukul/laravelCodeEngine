<?php

function apsisDateFormat()
{
  return 'd-m-Y';
}

function mySqlDateFormat()
{
  return 'Y-m-d';
}

function apsisDateTimeFormat()
{
  return 'd-m-Y h:i A';
}

function mySqlDateTimeFormat()
{
  return 'Y-m-d h:i:s';
}

function apsisDate($date = '')
{
  $format = apsisDateFormat();
  $apsisDate = date($format);
  if (!empty($date)) {
    $timestamp = strtotime($date);
    $apsisDate = date($format, $timestamp);
  }
  return $apsisDate;
}

function mySqlDate($date = '')
{
  $format = mySqlDateFormat();
  $mySqlDate = date($format);
  if (!empty($date)) {
    $timestamp = strtotime($date);
    $mySqlDate = date($format, $timestamp);
  }
  return $mySqlDate;
}

function apsisDateTime($date = '')
{
  $format = apsisDateTimeFormat();
  $apsisDateTime = date($format);
  if (!empty($date)) {
    $timestamp = strtotime($date);
    $apsisDateTime = date($format, $timestamp);
  }
  return $apsisDateTime;
}

function mySqlDateTime($date = '')
{
  $format = mySqlDateTimeFormat();
  $mySqlDateTime = date($format);
  if (!empty($date)) {
    $timestamp = strtotime($date);
    $mySqlDateTime = date($format, $timestamp);
  }
  return $mySqlDateTime;
}

function doUpload($upload_dir, $field_name)
{
  $upload_path = base_path($upload_dir);
  makeDirectory($upload_path);
  $file_name = time() . '.' . $field_name->getClientOriginalExtension();
  $field_name->move($upload_path, $file_name);
  return $file_name;
}

function apsisAmount($amount)
{
  return number_format($amount, 2);
}

function makeDirectory($path)
{
  if (!is_dir($path)) {
    mkdir($path, 0777, TRUE);
  }
}

function fileDelete($path)
{
  if (file_exists($path)) {
    //return @unlink($path);
    File::delete($path);
  }
}
