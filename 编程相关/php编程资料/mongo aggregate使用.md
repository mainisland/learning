#mongodb aggregateʹ��

����Ҫ����ͳ��������ֽ��������ʹ���˷�ɫ���ܵ�������
���ݼ������£�

    {
    "_id"           : ObjectId,
    "cuid"          : string,       // &cuid �û�uid
    "date"          : string,       //���ڣ�׼ȷ����
    "menses"        :[
        "status"    : int,          //1:������0:û����
        "dysmenorrhea" : int,       //ʹ���ȼ���0��5��0��ʾ��ʹ���ȼ�Խ��Խʹ
        "aa"        : [
            {
                "type"  : int,      //1��ͬ����2������ͬ����
                "time"  : string,   //ͬ��ʱ�䣺��10��00��
                "post"  : int,      //���ƣ�0:���� 1:��ϥλ 2:����λ
                "like"  : int,      //�����Ϲ��� 0: ���� 1 ��
            }
        ],
        "ovulate"       : int,      //1: ���ѡ�0��û����
        "temperature"   : double,   //����
        "weight"        : double,   //����(����2014-7-21��)
        "drug"          : int,      //1: ��ҩ��0: û��ҩ
        "night"         : int,      //1: ˯�߲��㡣0: ˯����
        "page"          : [
            {
                "color" : int,      //��ֽ��ɫֵ
                "time"  : string,   //��ֽʱ�䣺(10:00)
            }
        ],
        "gongjing"      : [         //�����Һ
            "waiyin"    : int,      //�����о�: 1.���� 2.ʪ 3 ��ʪ
            "nianye"    : int,      //�Һ״̬: 1.ճ�� 2.ϡ��
        ],
        "baidai"        : int,     //1:�״���˿ 0:�״�����˿
        "pregnancy"     : int,     //1:���� 0:û����
        "zhengzhuang"   : string,  //"":û��,"1,2,3":��ʾ��֢״
        "remarks"       : string,  //��ע
    ]
    }

ͳ��������db.crazy_period.aggregate({'$unwind':"$menses.page"},{'$group':{'_id':'null','course':{'$sum':1}}})

ͳ��ʹ�÷�ɫ������:
db.crazy_period.aggregate({'$match':{'menses.page.color':{'$ne':0}}},{'$unwind':"$menses.page"},{'$group':{'_id':'null','course':{'$sum':1}}})

mongodb�ж������Ƿ���ڣ�
db.crazy_period.count({'menses.page.0':{'$exists':true}})