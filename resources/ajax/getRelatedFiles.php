<?php
include_once("../../app/Database.php");
include_once("../../app/App.php");
include_once("../../app/Auth.php");
$auth = new \app\Auth();
$app = new \app\App();
if($_POST['ft_guid']){
    $relatedFiles = $app->getRelatedFiles($_POST['ft_guid']);
    if(!empty($relatedFiles)){
        $i=0;
        foreach($relatedFiles  as $relatedFile){
            $i++;
            ?>
            <a class="list-group-item font-weight-bold" href="<?php echo $relatedFile['file_path'] ?>" target="_blank" role="tab">
                <?php echo '<span class="text-uppercase">'.$i.'.) '.$relatedFile['original_filename'].'</span>'?>
            </a>
        <?php }
    }else{
        echo '<a class="list-group-item font-weight-bold">No suggestion found</a>';
    }
}

