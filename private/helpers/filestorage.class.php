<?php


class FileStorage extends Base
{
    
    
    private $_contents = array();

    public function save($fileName, $content)
    {
        if (file_put_contents($fileName, $content) === false)
        {
            System::error(11138, $fileName);
        }
        $this->_contents[$fileName] = $content;
        return true;
    }

    public function get($fileName)
    {
        if (isset($this->_contents[$fileName]))
        {
            return $this->_contents[$fileName];
        }
        if (!is_file($fileName))
        {
            return false;
        }
        $content = file_get_contents($fileName);
        $this->_contents[$fileName] = $content;
        return $content;
    }
}
