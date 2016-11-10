<?php
namespace res\waterMonitor\frontend\controllers;

use Yii;

class Socketserver{
  private $_port='9000';
  private $_address='127.0.0.1';
  private $_client_socket_list=array();
  public function __set($name,$val){
    $this->$name=$val;
  }
  private function _showError($error){
    exit($error);
  }
  /**
   * ��ʼ����socket�������˼����˿�
   */
  public function start(){
    // �����˿�
    if (($sock = socket_create ( AF_INET, SOCK_STREAM, SOL_TCP )) === false) {
      $this->_showError("socket_create() failed :reason:" . socket_strerror ( socket_last_error () ));
    }
    // ��
    if (socket_bind ( $sock, $this->_address, $this->_port ) === false) {
      $this->_showError("socket_bind() failed :reason:" . socket_strerror ( socket_last_error ( $sock ) ));
    }
    // ����
    if (socket_listen ( $sock, 5 ) === false) {
      $this->_showError("socket_bind() failed :reason:" . socket_strerror ( socket_last_error ( $sock ) ) );
    }
    do {
      //����һ���ͻ������ӵ�ʱ��
      if ($client_socket=socket_accept ( $sock )) {
        $count = count ( $this->_client_socket_list ) + 1;
        //���������û����� �ͻ���������
        $this->_client_socket_list[]=$client_socket;
        echo "new connection:\r\n";//�������������ǰ�������ӵĿͻ�������
        echo "current connection:{$count}\r\n";
        //���ܿͻ��˴��������ַ���
        $msg=$this->read($client_socket);
        echo "client:{$msg}\r\n";
        //��������ͻ��˴�ֵ
        $my_msg="I am fine,think you\r\n";
        $this->send($client_socket,$my_msg);
      }
      /**
       * ��δ������ο�,�����ж��Ƿ��пͻ�������ʧȥ����
      else{
        foreach ( $this->_client_socket_list as $socket ) {
          $len = socket_recv ($socket, $buffer, 2048, 0 ); // ����һ�¿ͻ�����Ϣ,���Ϊ0����Ͽ�����
          if ($len < 7) {
            //����д��ȥ���ӵĿͻ���ҵ��
          }
        }
      }
       */
    }while(true);  
  }
  /**
   * �������ݸ��ͻ���
   */
  public function send($client_socket,$str){ 
    return socket_write ( $client_socket,$str, strlen ( $str ) );
  }
  /**
   * �ӿͻ��˽�������
   */
  public function read($client_socket){
    return socket_read ( $client_socket, 8192 );//8192ʵ�ʴ���Ľ��ܳ���,����819292��ʾ��һ��,������һ����ַ���Ҳ���Խ��ܵ�,����8192Ҳû��ϵ,���Զ�ʶ��
  }
}
//$socket_server =new SocketServer();
//$socket_server->start();//��ʼ����
