<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/* 
 *  ======================================= 
 *  Author     : Muhammad Surya Ikhsanudin 
 *  License    : Protected 
 *  Email      : mutofiyah@gmail.com 
 *   
 *  Dilarang merubah, mengganti dan mendistribusikan 
 *  ulang tanpa sepengetahuan Author 
 *  ======================================= 
 */  
require_once "PHPExcel.php"; 
/**
* @brief Libraries Class yang terkait dengan mengkonversi data ke excel
*
* Class Libraries ini untuk mengelola tampilan excel
* memanfaatkan semua fitur yang tersedia dalam excel
*
* @author Praba Santika
*
*/ 
class Excel extends PHPExcel { 
    public function __construct() { 
        parent::__construct(); 
    } 
}