<p><span>Search</span> - <a href="searchme.php">reset</a></p>
<?php $paths = array('/', '/app', '/config', '/public', '/resources', '/vendor'); ?>
<form method="post">
    <label for="keyword">Keyword:</label>
    <input type="text" name="keyword" id="keyword" value="<?=(isset($_POST['keyword']))?$_POST['keyword']:'';?>" />
    <select name="path"> 
        <?php 
            foreach ($paths as $path) {
                $_selected=''; 
                if(isset($_POST['path']) && $_POST['path'] == $path) {$_selected = 'selected="selected"';}
                echo '<option value="' . $path . '" '.$_selected.'>' . $path . '</option>'; 
            } 
        ?> 
    </select>
    <select name="file_ext"> 
        <option value="all">All</option>
        <?php
            $searchExtList = array('.php', '.html', '.xml', '.phtml', '.css', '.js'); 
            foreach ($searchExtList as $k => $_ext) {
                $_selected = '';
                if(isset($_POST['file_ext']) && $_POST['file_ext'] == $_ext) {$_selected = 'selected="selected"';}
                echo '<option value="' . $_ext . '" '.$_selected.'>' . $_ext . '</option>'; 
            }
        ?>
    </select>
    <input type="submit" value="search" >
</form>
<?php
    function everythingFrom($baseDir,$extList,$searchStr) 
    {
        $files='';
        $ob = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($baseDir), RecursiveIteratorIterator::SELF_FIRST);
        foreach($ob as $name => $object)
        {
            if (is_file($name)) {
                foreach($extList as $k => $ext) {
                    if (@substr($name,(strlen($ext) * -1)) == $ext) {
                        $tmp = file_get_contents($name);
                        if (@strpos($tmp,$searchStr) !== false) {
                            $files .= str_replace('./',"",$name) .'<br />';
                        }
                    }
                }
            }
        }    
        if($files) return $files; else return 'No Result';

    }

    if($_POST){ 
        $searchword = $_POST['keyword'];
        $folder = $_POST['path'];
        $file_ext = $_POST['file_ext'];
        if($file_ext == 'all'){
            $searchExtList = array('.php', '.html', '.xml', '.phtml', '.css', '.js');
        }else{
            $searchExtList = array($file_ext);
        }
        $allFiles = everythingFrom('.'.$folder,$searchExtList,$searchword);
        echo '<pre>';print_r($allFiles);echo '</pre>';

        /*echo "<br />================================================================<br /><br />";
        $command = "grep -ri '".$searchword."' ./".$folder;
        $output = shell_exec($command);
        echo str_replace(".//","",nl2br($output))."<br />\n";    
        echo "<br />Grep job over.";*/

    }

?>