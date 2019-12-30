<?php


class FileManager
{

    public function writeFile($file_name, $content){
        $myfile = fopen($file_name . ".txt", "w") or die("Unable to open file!");
        fwrite($myfile, $content);
        fclose($myfile);
    }

    public function readFile($file_name){
        $myfile = fopen($file_name . ".txt", "r") or die("Unable to open file!");
        $content = fread($myfile, filesize($file_name . ".txt"));
        fclose($myfile);
        return $content;
    }

}

