# GBox软件源制作教程

GBox软件源旨在构建一个ipa分享生态圈，采用json格式规范。制作软件源无需使用自己独立的服务器，可将软件源文件，和相关资源（如ipa包，图片素材等）上传至公开的公共平台，如Github等 (直接支持蓝奏云的链接，请看以下例子)

- #### 软件源制作流程
1. 新建一个空文本文件，按规下文范编写软件源json文件，例子如下:
```
{
    "version": "1.0",
    "sourceName": "GBox官方软件源",
    "sourceAuthor": "GBox Official",
    "sourceLinkTitle": "主页",
    "sourceLinkUrl": "https://gbox.run",
    "sourceImage": "https://gbox.run/Public/images/source.png",
    "sourceUpdateTime": "2019-11-23T11:00:00+0800", 
    "sourceDescription": "GBox官方软件源的描述",
    "appCategories": [
        "工具"
    ],
    "appRepositories": [
        {
            "appType": "ENT_SIGN",
            "appCateIndex": 0,
            "appUpdateTime": "2019-11-23T08:00:00+0800",
            "appName": "GBox",
            "appVersion": "1.3.6",
            "appImage": "https://gbox.run/Public/images/icon.png",
            "appPackage": "https://dev.tencent.com/u/wallace_leung/p/AppHub/git/raw/master/Xero/GBox_v1.3.6_1122.ipa",
            "appDescription": "App的描述"
        },

        {
            "appType": "SELF_SIGN",
            "appCateIndex": 1,
            "appUpdateTime": "2019-11-23T08:00:00+0800",
            "appName": "LSearch",
            "appVersion": "1.0",
            "appPlist": "https://www.lanzous.com/i7rn4hi",
            "appDescription": "VIP测试用，点下载，然后签名，再安装，如果能正常打开，则证明VIP成功"
        },

        {
            "appType": "LINK",
            "appCateIndex": 0,
            "appUpdateTime": "2019-12-27T9:30:00+0800",
            "appName": "GBox官网",
            "appVersion": "1.0",
            "appImage": "https://dev.tencent.com/u/wallace_leung/p/AppHub/git/raw/master/Xero/icon512.png",
            "appLink": "https://gbox.run",
            "appDescription": "网页连接测试"
        },
    ]
}
```

2. 所编写的源内容必须为合法json，可以使用 [json.cn](https://www.json.cn/) 进行语法检测

3. 将源内容保存以.appsrc后缀结尾的文本文件，例如gbox.appsrc，将其导入至GBox中，然后点击该文件，再点击加密，即可生成加密后的源文件(加密不是必须的，但为了保护自身劳动成果，建议都加密)，上面例子加密后的内容如下:
```
{
  "appCategories" : [
    "福利",
    "工具"
  ],
  "sourceUpdateTime" : "2019-10-10T13:00+0800",
  "sourceAuthor" : "Rosi",
  "sourceDescription" : "Rosi美女图片源的描述",
  "version" : "1.0",
  "sourceName" : "Rosi美女图片源",
  "sourceImage" : "http:\/\/apiv2.prettybeauty.biz\/Public\/resources\/icon256.png",
  "appRepositories" : "AwGTxWfhOXLcvwhKgAfRe6YZfcmAYxS3ZGyfRz1b1oBBTbs+X5epLINZSU8Idc9aMX5bF+zIicp99o1PvrKM6UeaRDoYzBJADisd8nQLykP\/HtfvemdPjZJJmmweoS2yEImdUwwq1b7GweSfnLvkG0G9rCBqOhg6oZGZV7wYpuSZAUXkoClKOD6DEDVapJvekp3ee0seNWwtR7LUI32Y\/wkQ+WhCL68+wzYw7Wqwh4cPkv\/NOMgZgYffDQG8tNT1PYjHHkWiF51zbn9yNtERp7cPcOUG0nUxCF9CnZOhYkmxenkuQgql1eHvglp34A77RR3hICPrp7tc05r2esIb0Z9aC66KxF6mvq2WDOmikX6qDIz6OCtD56oTcLwORR3tnEIGYXuE9dDVkBohDsxM1NhJ+DQqnkc7EiNQ6LVTI1bkbgQQJQ9iqWpA5A+S3\/1pY0+hY5H\/lSIUCAmmWZplGM1Yi5CU\/uNevfSTKecRWeu25pN+P9AfsVhIBH76DgsOGRkIzPCIv\/ZcNnW50nmffYh\/hxTNsa9obm112kB9rXtF2oiVy2ewf2jo87Y+id4cbXoFCwVppwCiDgBVnLoRamxxyF4tauPovm26yrA3GKXDBESQFHBvafBmn9P6eYEyetlzh6FY+mUND8HbnVhjuOLG\/hkdqrEuvEFCkvJv2lq5gn9x0Jj3XvHqcWAG0vabvoc2WRKNorZyOaFEagtNurWFUHMBK2aReA\/c44faV3du48EMXMib\/4u7gv6OVc7EaPKyReFEUiYTAPjYwxnF68Bc2YfR17Ux4pIgBPubShBCyqGq3Wgoj6rbOVUPXKXfMeZpg3QFp6VCy2D30TKwm679VvGL6evHPQ4Jj7qn8QVTfICYWfs9t+QPPqhfUM3eqQj8yzU3oOCyeU9BarAkBOna8yFl4undec72+P1vim6dAKhSBwcwXmsJ\/p2v9LAUDkGmTmwxLaVFVaYLzHw5xAGHIVNW4BwKEXz5sRw\/+NqVWPTQ2gHa9ehPoMm8Jv6B09dQGz7ATgAubh9NT1P97dWJxGzORBQTulLNMUsnPGCvHCLdkxs1lbZyXxhcWOMVAaBXiA3y5In1ITzycszoLiHuGmf4Ss6B+d27SAcEh57I6PMsJOzRyKbAvs6yYwQp\/pE3q3HsbHHWFzjlLhRI5VmCp88fVXrytT+VXaPcWMYXp6M5L6Sty3NS2G+4MCiH79wsbnvrUXBJc5cSKjVsmxp\/"
}
```

4. 将其上传至可公开访问的平台上，例如GitHub，码云，Coding等，得到链接（git平台请复制raw链接），至此即可在GBox中添加该链接，稍等片刻，即可加载源中的app列表

- ### 1.1.2新增内容     
1. 增加app隐藏功能，在app对象中设置hide为true，即不会在列表中显示。
2. 增加源授权功能，即可设置部分或全部app为上锁状态，需要输入密码才能解锁，以下为新增字段:
> 注意：开启授权源，源必须使用GBox加密，否则无效。此为一次性解锁全部上锁的app
> 原理：进行加密时，会分离字段lock为true的app下载链接。生成2份文件，即一份为已加密的源文件，另外一份为分离后的app真实下载地址的加密文件(此文件需要上传到服务器)
> AuthBackendSmapleCode文件夹内附带后台验证逻辑样例，仅供参考，可自行改进完善
```
{
    ...,
    sourceProcessor: {
        appsUnlock: {
            authUrl: "https://xxx.com/auth.php",       # 验证地址，即进行解锁时，所提交的密码将传到此链接进行权限校验
            actions: [                                 # 解锁弹框所显示的选项，此为数组，即可显示多项
                {
                title: "联系作者",                      # 解锁弹框的选项标题
                openUrl: "https://nyxz166.cn/GBox"     # 解锁弹框的选项标题所对应的具体行为，点击后，会跳转到本链接
                }
            ],
            description: "此源需要联系作者授权"           # 源解锁的说明
        }
    },
    
    "appRepositories": [
        {
            "lock": true,                              # 需要上锁的app，添加此字段，并设置为true
            "hide": true,                              # 非必须字段，若添加此字段，则解锁前隐藏，解锁后才显示
            "appType": "SELF_SIGN",
            "appCateIndex": 1,
            "appUpdateTime": "2020-05-01T08:00:00+0800",
            "appName": "测试包",
            "appVersion": "1.0",
            "appPlist": "https://www.lanzous.com/i7rn4hi",
            "appDescription": "签名测试用，点下载，然后签名，再安装，如果能正常打开，则证明签名成功"
        },
        ....
    ]

},
```

- ### 源规范说明
>以下为完整源规范说明，已更新至v1.1.2
```
{
    "version": "1.1.2",   # 源版本号，当前为1.1.2， 只能向下兼容
    "sourceName": "GBox官方源",     # 源名称 
    "sourceAuthor": "Rosi",        # 源作者
    "sourceLinkTitle": "主页",      # 链接标题，将于源主页的右上角显示 
    "sourceLinkUrl": "https://gbox.run",       # 目标链接
    "sourceImage": "http://xxx.com/src.png",   # 源图片链接
    "sourceUpdateTime": "2019-10-10T13:00:00+0800",    # 源更新时间(此为UTC时间，北京为东八区，所以以+0800表示)，注意如果修改了app列表，而不更新此时间，GBox不会去解析此源的app列表
    "sourceDescription": "源的简单描述",

    # 授权源字段说明
    sourceProcessor: {
        appsUnlock: {
            authUrl: "https://xxx.com/auth.php",       # 验证地址，即进行解锁时，所提交的密码将传到此链接进行权限校验
            actions: [                                 # 解锁弹框所显示的选项，此为数组，即可显示多项
                {
                title: "联系作者",                      # 解锁弹框的选项标题
                openUrl: "https://nyxz166.cn/GBox"     # 解锁弹框的选项标题所对应的具体行为，点击后，会跳转到本链接
                }
            ],
            description: "此源需要联系作者授权"           # 源解锁的说明
        }
    },
    # End 授权源字段说明


    # 源的app分类排序，以0为起始计数，如在此排序中"福利"分类的下标为0，"工具"则为1
    "appCategories": [
        "福利",
        "工具"
    ],
    "appRepositories": [
        {
            # app类型，共有三种类型:
            # SHAREING 分享类型，即直接提取app store得来的ipa，不能签名，直接安装，需要在app store登录对应的购买购买账号，即可正常使用，永不掉签
            # SELF_SIGN 自签类型，需要签名后，才能正常安装，否则安装后图标为灰色不可用
            # ENT_SIGN 企业签类型，如果该企业签未被注销，则可直接安装，否则需要签名后安装
            # LINK  链接类型，直接打开网址，广告或宣传用途，需要填写appLink字段
            "appType": "SHAREING",      
            "appCateIndex": 0,      # app分类下标号，此处为0对应上面的分类"福利"
            "appUpdateTime": "2019-10-13T08:00:00+0800", # UTC时间
            "appName": "App名称",
            "appVersion": "1.1",    # app版本号
            "appImage": "https://xxx.com/app.png",      # app图标链接
            "appPackage": "https://xx.com/app.ipa",      # app的ipa包链接
            "appDescription": "App的简短描述"
        },

        {
            "appType": "ENT_SIGN",  # 企业签
            "appCateIndex": 1,      
            "appUpdateTime": "2019-10-03T08:00:00+0800",
            "appName": "xxx",

            # 此字段为app的plist配置链接，如果设置了此字段，appName，appVersion， appImage，appPackage都可省略, 但以上字段如果有设置，则优先级更高
            "appPlist": "https://xxx.plist"
        },

        {
            "appType": "LINK",  # 链接类型，需要填写appLink字段
            "appCateIndex": 1,
            "appUpdateTime": "2019-12-27T9:30:00+0800",
            "appName": "GBox官网",
            "appVersion": "1.0",
            "appImage": "https://dev.tencent.com/u/wallace_leung/p/AppHub/git/raw/master/Xero/icon512.png",
            "appLink": "https://gbox.run",  # 目录链接
            "appDescription": "网页连接测试"
        },

        # 上锁app样例
        {
            "lock": true,                              # 需要上锁的app，添加此字段，并设置为true
            "hide": true,                              # 非必须字段，若添加此字段，则解锁前隐藏，解锁后才显示
            "appType": "SELF_SIGN",
            "appCateIndex": 1,
            "appUpdateTime": "2020-05-01T08:00:00+0800",
            "appName": "测试包",
            "appVersion": "1.0",
            "appPlist": "https://www.lanzous.com/i7rn4hi",
            "appDescription": "签名测试用，点下载，然后签名，再安装，如果能正常打开，则证明签名成功"
        }
    ]
}
```

