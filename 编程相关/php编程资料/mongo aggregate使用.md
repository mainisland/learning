#mongodb aggregate使用

今天要分组统计排卵试纸的条数和使用了反色功能的条数；
数据几个如下：

    {
    "_id"           : ObjectId,
    "cuid"          : string,       // &cuid 用户uid
    "date"          : string,       //日期，准确到天
    "menses"        :[
        "status"    : int,          //1:来潮。0:没来潮
        "dysmenorrhea" : int,       //痛经等级，0～5。0表示不痛。等级越高越痛
        "aa"        : [
            {
                "type"  : int,      //1：同房。2：建议同房。
                "time"  : string,   //同房时间：（10：00）
                "post"  : int,      //姿势，0:其他 1:胸膝位 2:仰卧位
                "like"  : int,      //赞下老公， 0: 不赞 1 赞
            }
        ],
        "ovulate"       : int,      //1: 排卵。0：没排卵
        "temperature"   : double,   //体温
        "weight"        : double,   //体重(新增2014-7-21日)
        "drug"          : int,      //1: 吃药。0: 没吃药
        "night"         : int,      //1: 睡眠不足。0: 睡眠足
        "page"          : [
            {
                "color" : int,      //试纸颜色值
                "time"  : string,   //试纸时间：(10:00)
            }
        ],
        "gongjing"      : [         //宫颈黏液
            "waiyin"    : int,      //外阴感觉: 1.干燥 2.湿 3 很湿
            "nianye"    : int,      //黏液状态: 1.粘稠 2.稀滑
        ],
        "baidai"        : int,     //1:白带拉丝 0:白带不拉丝
        "pregnancy"     : int,     //1:怀孕 0:没怀孕
        "zhengzhuang"   : string,  //"":没有,"1,2,3":表示有症状
        "remarks"       : string,  //备注
    ]
    }

统计总数：db.crazy_period.aggregate({'$unwind':"$menses.page"},{'$group':{'_id':'null','course':{'$sum':1}}})

统计使用反色的人数:
db.crazy_period.aggregate({'$match':{'menses.page.color':{'$ne':0}}},{'$unwind':"$menses.page"},{'$group':{'_id':'null','course':{'$sum':1}}})

mongodb判断数组是否存在：
db.crazy_period.count({'menses.page.0':{'$exists':true}})