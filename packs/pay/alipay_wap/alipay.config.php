<?php

//�����������������������������������Ļ�����Ϣ������������������������������
//���������id����2088��ͷ��16λ������
$alipay_config['partner']		= CS_Alipay_ID;

//��ȫ�����룬�����ֺ���ĸ��ɵ�32λ�ַ�
//���ǩ����ʽ����Ϊ��MD5��ʱ�������øò���
$alipay_config['key']			= CS_Alipay_Key;

//�̻���˽Կ����׺��.pen���ļ����·��
//���ǩ����ʽ����Ϊ��0001��ʱ�������øò���
$alipay_config['private_key_path']	= 'key/rsa_private_key.pem';

//֧������Կ����׺��.pen���ļ����·��
//���ǩ����ʽ����Ϊ��0001��ʱ�������øò���
$alipay_config['ali_public_key_path']= 'key/alipay_public_key.pem';


//�����������������������������������Ļ�����Ϣ������������������������������

//ǩ����ʽ �����޸�
$alipay_config['sign_type']    = '0001';

//�ַ������ʽ Ŀǰ֧�� gbk �� utf-8
$alipay_config['input_charset']= 'gbk';

//ca֤��·����ַ������curl��sslУ��
//�뱣֤cacert.pem�ļ��ڵ�ǰ�ļ���Ŀ¼��
$alipay_config['cacert']    = str_replace("/", "\\",CSCMSPATH."pay\\alipay_wap\\cacert.pem");

//����ģʽ,�����Լ��ķ������Ƿ�֧��ssl���ʣ���֧����ѡ��https������֧����ѡ��http
$alipay_config['transport']    = 'http';
?>

