SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for detail
-- ----------------------------
DROP TABLE IF EXISTS `detail`;
CREATE TABLE `detail` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `ClassID` int(11) DEFAULT NULL,
  `Author` varchar(255) DEFAULT NULL,
  `Source` varchar(255) DEFAULT NULL,
  `CreateTime` bigint(20) DEFAULT NULL,
  `UpdateTime` bigint(20) DEFAULT NULL,
  `Count` int(11) DEFAULT NULL,
  `Title` varchar(255) DEFAULT NULL,
  `Content` text,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of detail
-- ----------------------------

-- ----------------------------
-- Table structure for img
-- ----------------------------
DROP TABLE IF EXISTS `img`;
CREATE TABLE `img` (
  `ClassID` int(11) DEFAULT NULL,
  `ID` int(11) DEFAULT NULL,
  `Display` int(11) DEFAULT NULL,
  `Title` varchar(255) DEFAULT NULL,
  `Url` varchar(255) DEFAULT NULL,
  `Link` varchar(255) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of img
-- ----------------------------
INSERT INTO `img` VALUES ('1', '1', '1', null, '/Img/banner1.jpg', null);
INSERT INTO `img` VALUES ('1', '2', '0', null, '/Img/banner2.jpg', null);
INSERT INTO `img` VALUES ('1', '3', '0', null, '/Img/banner3.jpg', null);
INSERT INTO `img` VALUES ('1', '4', '0', null, '/Img/banner4.jpg', null);
INSERT INTO `img` VALUES ('2', '1', '1', null, '/Img/1.jpg', null);
INSERT INTO `img` VALUES ('2', '2', '0', null, '/Img/2.jpg', null);
INSERT INTO `img` VALUES ('2', '3', '0', null, '/Img/3.jpg', null);
INSERT INTO `img` VALUES ('2', '4', '0', null, '/Img/4.jpg', null);
INSERT INTO `img` VALUES ('2', '5', '0', null, '/Img/5.jpg', null);
INSERT INTO `img` VALUES ('2', '6', '0', null, '/Img/6.jpg', null);
INSERT INTO `img` VALUES ('2', '7', '0', null, '/Img/7.jpg', null);
INSERT INTO `img` VALUES ('2', '8', '0', null, '/Img/8.jpg', null);
INSERT INTO `img` VALUES ('2', '9', '0', null, '/Img/9.jpg', null);
INSERT INTO `img` VALUES ('2', '10', '0', null, '/Img/10.jpg', null);
INSERT INTO `img` VALUES ('3', '1', '1', null, '/Img/01.jpg', null);
INSERT INTO `img` VALUES ('3', '2', '0', null, '/Img/02.jpg', null);
INSERT INTO `img` VALUES ('3', '3', '0', null, '/Img/03.jpg', null);
INSERT INTO `img` VALUES ('3', '4', '0', null, '/Img/04.jpg', null);
INSERT INTO `img` VALUES ('3', '5', '0', null, '/Img/05.jpg', null);
INSERT INTO `img` VALUES ('3', '6', '0', null, '/Img/06.jpg', null);
INSERT INTO `img` VALUES ('3', '7', '0', null, '/Img/07.jpg', null);
INSERT INTO `img` VALUES ('3', '8', '0', null, '/Img/08.jpg', null);
INSERT INTO `img` VALUES ('3', '9', '0', null, '/Img/09.jpg', null);
INSERT INTO `img` VALUES ('3', '10', '0', null, '/Img/010.jpg', null);

-- ----------------------------
-- Table structure for link
-- ----------------------------
DROP TABLE IF EXISTS `link`;
CREATE TABLE `link` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `Title` varchar(255) DEFAULT NULL,
  `Url` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of link
-- ----------------------------
INSERT INTO `link` VALUES ('1', '百度一下，你就知道', 'http://www.baidu.com/');

-- ----------------------------
-- Table structure for list
-- ----------------------------
DROP TABLE IF EXISTS `list`;
CREATE TABLE `list` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `ParentID` int(11) DEFAULT NULL,
  `OrderID` int(11) DEFAULT NULL,
  `Name` varchar(255) DEFAULT NULL,
  `Type` int(11) DEFAULT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of list
-- ----------------------------
INSERT INTO `list` VALUES ('1', '0', '1', '首页', '0');

-- ----------------------------
-- Table structure for list_detail
-- ----------------------------
DROP TABLE IF EXISTS `list_detail`;
CREATE TABLE `list_detail` (
  `ID` int(11) DEFAULT NULL,
  `Title` varchar(255) DEFAULT NULL,
  `Content` text
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of list_detail
-- ----------------------------

-- ----------------------------
-- Table structure for site
-- ----------------------------
DROP TABLE IF EXISTS `site`;
CREATE TABLE `site` (
  `Title` varchar(255) DEFAULT NULL,
  `Author` varchar(255) DEFAULT NULL,
  `Keywords` text,
  `Description` text,
  `Footer` text,
  `Salt` varchar(255) DEFAULT NULL,
  `Password` varchar(255) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of site
-- ----------------------------
INSERT INTO `site` VALUES ('网站名称', '', '', '', '', '9c7a5f66691981001607038d8ffbb115', '405baf00781c1754e9f9e9f8ff918264');
