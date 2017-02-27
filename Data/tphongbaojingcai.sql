SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;


CREATE TABLE IF NOT EXISTS `haokuai_fenxiao` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fjine1` int(11) NOT NULL DEFAULT '0',
  `fjine2` int(11) NOT NULL DEFAULT '0',
  `fjine3` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `haokuai_hb` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `hzhifue` int(11) NOT NULL DEFAULT '0',
  `hminmoney` int(11) NOT NULL DEFAULT '0',
  `hmaxmoney` int(11) NOT NULL DEFAULT '0',
  `hgeshu` int(11) NOT NULL DEFAULT '0',
  `hbianhua` int(11) NOT NULL DEFAULT '0',
  `hlastbian` int(11) NOT NULL DEFAULT '0',
  `hpaixu` int(11) NOT NULL DEFAULT '0',
  `hb` varchar(300) NOT NULL DEFAULT '',
  `hcode` int(2) NOT NULL DEFAULT '1',
  `htype` int(2) NOT NULL DEFAULT '1',
  `htime` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `hbianhua` (`hbianhua`),
  KEY `hcode` (`hcode`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `haokuai_hb_gailv` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `hbid` int(11) NOT NULL DEFAULT '0',
  `hmin` int(11) NOT NULL DEFAULT '0',
  `hmax` int(11) NOT NULL DEFAULT '0',
  `hgailv` int(11) NOT NULL DEFAULT '0',
  `hjiaodu` int(11) NOT NULL DEFAULT '0',
  `hcishu` int(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `hbid` (`hbid`),
  KEY `hcishu` (`hcishu`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `haokuai_news` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ncontent` text NOT NULL,
  `ntype` int(2) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `ntype` (`ntype`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `haokuai_saolei_hbset` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `hbzhifu` int(11) NOT NULL DEFAULT '0',
  `hgeshu` int(11) NOT NULL DEFAULT '0',
  `hweiduo` int(11) NOT NULL DEFAULT '0',
  `hweishao` int(11) NOT NULL DEFAULT '0',
  `hpaixu` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `haokuai_saolei_linghb` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `userid` int(11) NOT NULL DEFAULT '0',
  `hblistid` int(11) NOT NULL DEFAULT '0',
  `hbe` int(11) NOT NULL DEFAULT '0',
  `hcode` int(2) NOT NULL DEFAULT '1',
  `ttime` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `userid` (`userid`),
  KEY `hblistid` (`hblistid`),
  KEY `hcode` (`hcode`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `haokuai_saolei_peifu` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `userid` int(11) NOT NULL DEFAULT '0',
  `fauserid` int(11) NOT NULL DEFAULT '0',
  `hblistid` int(11) NOT NULL DEFAULT '0',
  `hpeie` int(11) NOT NULL DEFAULT '0',
  `ttime` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `userid` (`userid`),
  KEY `hblistid` (`hblistid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `haokuai_saolei_userfa` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `userid` int(11) NOT NULL DEFAULT '0',
  `hbid` int(11) NOT NULL DEFAULT '0',
  `hzhifue` int(11) NOT NULL DEFAULT '0',
  `hgeshu` int(11) NOT NULL DEFAULT '0',
  `hweishu` int(2) NOT NULL DEFAULT '0',
  `htime` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `userid` (`userid`),
  KEY `hbid` (`hbid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `haokuai_saolei_userfalist` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `faid` int(11) NOT NULL DEFAULT '0',
  `hmoney` int(11) NOT NULL DEFAULT '0',
  `hweishu` int(11) NOT NULL DEFAULT '0',
  `hcode` int(4) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `faid` (`faid`),
  KEY `hcode` (`hcode`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `haokuai_sys_config` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cduanlianjie` int(2) NOT NULL DEFAULT '1',
  `ctongzhi` varchar(500) NOT NULL DEFAULT '',
  `cwxchoutxt` varchar(500) NOT NULL DEFAULT '',
  `cbeicode` int(2) NOT NULL DEFAULT '2',
  `cbeipay` int(11) NOT NULL DEFAULT '1',
  `cbeimchid` varchar(50) DEFAULT '',
  `cbeiappkey` varchar(50) DEFAULT '',
  `cbeiurl` varchar(150) NOT NULL DEFAULT '',
  `cbeiappid` varchar(50) NOT NULL DEFAULT '',
  `cbeiappsecret` varchar(50) NOT NULL DEFAULT '',
  `cwxappid` varchar(50) NOT NULL DEFAULT '',
  `cwxappsecret` varchar(50) NOT NULL DEFAULT '',
  `cwxappkey` varchar(40) NOT NULL DEFAULT '',
  `cwxmchid` varchar(30) NOT NULL DEFAULT '',
  `ckouliang` int(3) NOT NULL DEFAULT '0',
  `cyongjinfa` int(2) NOT NULL DEFAULT '1',
  `cyongjine` int(11) NOT NULL DEFAULT '0',
  `cdenglucode` int(3) NOT NULL DEFAULT '1',
  `cdailishengji` int(2) NOT NULL DEFAULT '1',
  `cdailicode` int(2) NOT NULL DEFAULT '1',
  `cmaurl` varchar(150) NOT NULL DEFAULT '',
  `cfaurl` varchar(150) NOT NULL DEFAULT '',
  `cpingbie` int(11) NOT NULL DEFAULT '0',
  `cchongzong` int(11) NOT NULL DEFAULT '0',
  `cgundong` int(4) NOT NULL DEFAULT '1',
  `adminid` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `haokuai_sys_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `lbiaoshi` varchar(50) NOT NULL DEFAULT '',
  `lcon` text,
  `ltime` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `haokuai_sys_maset` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `mleft` int(11) NOT NULL DEFAULT '0',
  `mtop` int(11) NOT NULL DEFAULT '0',
  `msize` int(11) NOT NULL DEFAULT '5',
  `midcolor` varchar(50) DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `haokuai_sys_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uname` varchar(20) NOT NULL,
  `upass` varchar(50) NOT NULL DEFAULT '',
  `utype` int(2) NOT NULL DEFAULT '1',
  `utime` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `haokuai_user_chongzhi` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `userid` int(11) NOT NULL DEFAULT '0',
  `ddanhao` varchar(50) NOT NULL DEFAULT '',
  `djine` int(11) NOT NULL DEFAULT '0',
  `dutid` int(11) NOT NULL DEFAULT '0',
  `dcode` int(3) NOT NULL DEFAULT '1',
  `djisuan` int(2) NOT NULL DEFAULT '1',
  `dtime` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `ddanhao` (`ddanhao`),
  KEY `userid` (`userid`),
  KEY `dcode` (`dcode`),
  KEY `dtime` (`dtime`)
) ENGINE=MyISAM  DEFAULT CHARSET=gbk;

CREATE TABLE IF NOT EXISTS `haokuai_user_duizhang` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `userid` int(11) NOT NULL DEFAULT '0',
  `paynum` int(11) NOT NULL DEFAULT '0',
  `wxnum` int(11) NOT NULL DEFAULT '0',
  `dcode` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `haokuai_user_hb` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `userid` int(11) NOT NULL DEFAULT '0',
  `hbid` int(11) NOT NULL DEFAULT '0',
  `hbe` int(11) NOT NULL DEFAULT '0',
  `tcode` int(2) NOT NULL DEFAULT '1',
  `ttime` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `userid` (`userid`),
  KEY `hbid` (`hbid`),
  KEY `tcode` (`tcode`),
  KEY `ttime` (`ttime`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `haokuai_user_list` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `utid` int(11) NOT NULL DEFAULT '0',
  `uopenid` varchar(50) NOT NULL DEFAULT '',
  `ubeiopenid` varchar(50) DEFAULT '',
  `uickname` varchar(80) DEFAULT '',
  `uheadimgurl` varchar(160) DEFAULT '',
  `usex` int(2) NOT NULL DEFAULT '0',
  `udizhi` varchar(40) DEFAULT '',
  `uvip` int(5) NOT NULL DEFAULT '0',
  `uhbcon` varchar(300) NOT NULL DEFAULT '',
  `ufacishu` int(5) NOT NULL DEFAULT '0',
  `ugengxin` int(11) NOT NULL DEFAULT '0',
  `ustate` int(11) NOT NULL DEFAULT '1',
  `ulogintime` int(11) NOT NULL DEFAULT '0',
  `uregtime` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `utid` (`utid`),
  KEY `uopenid` (`uopenid`),
  KEY `uvip` (`uvip`),
  KEY `ubeiopenid` (`ubeiopenid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `haokuai_user_tixian` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `userid` int(11) NOT NULL DEFAULT '0',
  `tixiane` int(11) NOT NULL DEFAULT '0',
  `ttime` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `tixiane` (`tixiane`),
  KEY `userid` (`userid`),
  KEY `ttime` (`ttime`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `haokuai_user_wxexcel` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `userid` int(11) NOT NULL DEFAULT '0',
  `uopenid` varchar(50) DEFAULT '',
  `wxdanhao` varchar(50) DEFAULT '',
  `wxjine` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `userid` (`userid`),
  KEY `uopenid` (`uopenid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `haokuai_user_yongjin` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `userid` int(11) NOT NULL DEFAULT '0',
  `uchong` int(11) NOT NULL DEFAULT '0',
  `tixiane` int(11) NOT NULL DEFAULT '0',
  `tcode` int(2) NOT NULL DEFAULT '1',
  `tjisuan` int(2) NOT NULL DEFAULT '1',
  `ttime` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `userid` (`userid`),
  KEY `tcode` (`tcode`)
) ENGINE=MyISAM  DEFAULT CHARSET=gbk;

CREATE TABLE IF NOT EXISTS `haokuai_user_zhanghu` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `userid` int(11) NOT NULL DEFAULT '0',
  `uhbqian` int(11) NOT NULL DEFAULT '0',
  `uqianzheng` int(11) NOT NULL DEFAULT '0',
  `uqianfa` int(11) NOT NULL DEFAULT '0',
  `uzhengzong` int(11) NOT NULL DEFAULT '0',
  `uqianchong` int(11) NOT NULL DEFAULT '0',
  `uchongzong` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `userid` (`userid`),
  KEY `uqianchong` (`uqianzheng`),
  KEY `uchongzong` (`uchongzong`),
  KEY `uzhengzong` (`uzhengzong`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `haokuai_weixin_caidan` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cid` int(11) NOT NULL DEFAULT '0',
  `cname` varchar(50) NOT NULL DEFAULT '',
  `ckey` varchar(50) NOT NULL DEFAULT '',
  `curl` varchar(150) NOT NULL DEFAULT '',
  `ctype` int(11) NOT NULL DEFAULT '0',
  `cnum` int(2) NOT NULL DEFAULT '1',
  `adminid` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `haokuai_weixin_mobanid` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `mbianhao` varchar(150) NOT NULL DEFAULT '',
  `mid` varchar(150) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `haokuai_weixin_sendcon` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `kid` int(11) NOT NULL DEFAULT '0',
  `sname` varchar(100) NOT NULL DEFAULT '',
  `sdec` varchar(500) NOT NULL DEFAULT '',
  `spic` varchar(50) NOT NULL DEFAULT '',
  `surl` varchar(150) NOT NULL DEFAULT '',
  `snum` int(2) NOT NULL DEFAULT '1',
  `stime` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `haokuai_weixin_sendkey` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `adminid` int(11) NOT NULL DEFAULT '0',
  `sname` varchar(100) NOT NULL DEFAULT '',
  `stype` int(2) NOT NULL DEFAULT '1',
  `kcode` int(2) NOT NULL DEFAULT '1',
  `stime` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `haokuai_yongjin_set` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ydengji` int(11) NOT NULL DEFAULT '0',
  `ybaifenbi` int(11) NOT NULL DEFAULT '0',
  `yjine` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `ydengji` (`ydengji`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
