<?php

  $id     = $_GET['id'];
  $delkey = $_GET['key'];

  if($id === null){
    exit;
  }

  //configをインクルード
  include('./config/config.php');
  $config = new config();
  $ret = $config->index();
  //配列キーが設定されている配列なら展開
  if (!is_null($ret)) {
    if(is_array($ret)){
      extract($ret);
    }
  }

  //データベースの作成・オープン
  try{
    $db = new PDO('sqlite:'.$db_directory.'/uploader.db');
  }catch (Exception $e){
    exit;
  }

  // デフォルトのフェッチモードを連想配列形式に設定
  // (毎回PDO::FETCH_ASSOCを指定する必要が無くなる)
  $db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

  // ファイル名取得
  $stmt = $db->prepare("SELECT * FROM uploaded WHERE id = :id");
  $stmt->bindValue(':id', $id); //ID
  $stmt->execute();
  $result = $stmt->fetchAll();
  foreach($result as $s){
    $filename = $s['origin_file_name'];
    $origin_delkey = $s['del_key'];
  }

  // ハッシュを照合して認証が通ればDEL可
  if ( PHP_MAJOR_VERSION == '5' and PHP_MINOR_VERSION == '3') {
    if( $delkey !== bin2hex(openssl_encrypt($origin_delkey,'aes-256-ecb',$key, true)) ){
      header('location: ./');
      exit;
    }
  }else{
    if( $delkey !== bin2hex(openssl_encrypt($origin_delkey,'aes-256-ecb',$key, OPENSSL_RAW_DATA)) ){
      header('location: ./');
      exit;
    }
  }

  // sqlから削除
  $sql = $db->prepare("DELETE FROM uploaded WHERE id = :id");
  $sql->bindValue(':id', $id); //ID
  if (! $sql->execute()) {
    // 削除を実施
  }

  //ディレクトリから削除
  $ext = substr( $filename, strrpos( $filename, '.') + 1);
  if ($encrypt_filename) {
    $path = $data_directory.'/' . 'file_' . str_replace(array('\\', '/', ':', '*', '?', '\"', '<', '>', '|'), '',openssl_encrypt($id,'aes-256-ecb',$key)) . '.'.$ext;
    if (!file_exists ( $path )) {
      $path = $data_directory.'/' . 'file_' . $id . '.'.$ext;
    }
  } else {
    $path = $data_directory.'/' . 'file_' . $id . '.'.$ext;
  }
  unlink($path);


  header('location: ./');
  exit();
