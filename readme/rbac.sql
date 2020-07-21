DROP TABLE IF EXISTS `admins`;

CREATE TABLE `admins` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL COMMENT '用户名',
  `password` varchar(128) NOT NULL COMMENT '密码',
  `email` varchar(128) NOT NULL COMMENT '邮箱',
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` int unsigned NOT NULL DEFAULT '0',
  `updated_at` int unsigned NOT NULL DEFAULT '0',
  `deleted_at` int unsigned NOT NULL DEFAULT '0' COMMENT '被删除的用户无法登录',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci COMMENT='后台用户表';

LOCK TABLES `admins` WRITE;

INSERT INTO `admins` (`id`, `name`, `password`, `email`, `remember_token`, `created_at`, `updated_at`, `deleted_at`)
VALUES
    (1,'admin','$2y$10$bzD2k32q050snhe5kbjP/.lBgV4CpqEf8spzZMm5XOb.l.DXNzif.','xiaoxi@qq.com','gdIg4uQBzVgJHTbQexBABO4Gz3VyXenWHYq1sDJbRft3lKit77sn5tfYw4cV',1553752888,1553752888,0),
    (2,'xiaoxi','$2y$10$bzD2k32q050snhe5kbjP/.lBgV4CpqEf8spzZMm5XOb.l.DXNzif.','xiaoxi@qq.com',NULL,1554265387,1554265387,0);

UNLOCK TABLES;

# ------------------------------------------------------------

DROP TABLE IF EXISTS `admin_role`;

CREATE TABLE `admin_role` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `admin_id` int(10) unsigned NOT NULL COMMENT '管理员ID',
  `role_id` int(10) unsigned NOT NULL COMMENT '角色ID',
  `created_at` int(10) unsigned NOT NULL DEFAULT 0,
  `updated_at` int(10) unsigned NOT NULL DEFAULT 0,
  `deleted_at` int(10) unsigned NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='管理员角色关系表';

LOCK TABLES `admin_role` WRITE;

INSERT INTO `admin_role` (`id`, `admin_id`, `role_id`, `created_at`, `updated_at`, `deleted_at`)
VALUES
  (1,1,1,1553752888,1553752888,0),
  (2,2,2,1554265387,1554265387,0);

UNLOCK TABLES;

# ------------------------------------------------------------

DROP TABLE IF EXISTS `roles`;

CREATE TABLE `roles` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(45) NOT NULL COMMENT '角色名称',
  `description` varchar(255) NOT NULL COMMENT '角色描述',
  `key` varchar(16) NOT NULL COMMENT '角色英文标识',
  `created_at` int(10) unsigned NOT NULL DEFAULT 0,
  `updated_at` int(10) unsigned NOT NULL DEFAULT 0,
  `deleted_at` int(10) unsigned NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='角色表';

LOCK TABLES `roles` WRITE;

INSERT INTO `roles` (`id`, `name`, `description`, `key`, `created_at`, `updated_at`, `deleted_at`)
VALUES
  (1,'超级管理员','拥有所有权限','super_admin',1553752888,1554211896,0),
  (2,'管理员','普通管理员','admin',1553752888,1554265477,0);

UNLOCK TABLES;

# ------------------------------------------------------------

DROP TABLE IF EXISTS `role_permission`;

CREATE TABLE `role_permission` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `role_id` int(10) unsigned NOT NULL COMMENT '角色ID',
  `permission_id` int(10) unsigned NOT NULL COMMENT '权限ID',
  `created_at` int(10) unsigned NOT NULL DEFAULT 0,
  `updated_at` int(10) unsigned NOT NULL DEFAULT 0,
  `deleted_at` int(10) unsigned NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='角色权限关系表';

LOCK TABLES `role_permission` WRITE;

INSERT INTO `role_permission` (`id`, `role_id`, `permission_id`, `created_at`, `updated_at`, `deleted_at`)
VALUES
  (1,1,1,1554211896,1554211896,0),
  (2,1,2,1554211896,1554211896,0);

UNLOCK TABLES;

# ------------------------------------------------------------

DROP TABLE IF EXISTS `permissions`;

CREATE TABLE `permissions` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(45) NOT NULL COMMENT '权限名称',
  `description` varchar(255) NOT NULL COMMENT '权限描述',
  `pid` int(10) unsigned NOT NULL DEFAULT 0 COMMENT '父级权限 顶级权限为 0',
  `icon` varchar(45) NOT NULL DEFAULT '' COMMENT '权限 菜单图标 只有顶级权限才有',
  `route` varchar(255) NOT NULL DEFAULT '' COMMENT '权限路由',
  `created_at` int(10) unsigned NOT NULL DEFAULT 0,
  `updated_at` int(10) unsigned NOT NULL DEFAULT 0,
  `deleted_at` int(10) unsigned NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='权限表';

LOCK TABLES `permissions` WRITE;

INSERT INTO `permissions` (`id`, `name`, `description`, `pid`, `icon`, `route`, `created_at`, `updated_at`, `deleted_at`)
VALUES
  (1,'权限管理','菜单 - 权限管理',0,'vercode','',1553752888,1553752888,0),
  (2,'角色列表','菜单 -- 角色列表',1,'','admin.roles.index',1553752888,1553752888,0),
  (3,'权限列表','菜单 - 权限列表',1,'','admin.permissions.index',1553752888,1553752888,0),
  (4,'管理员列表','管理员列表',1,'','admin.admins.index',1553752888,1553752888,0),
  (5,'添加权限页面','添加权限页面',3,'','admin.permissions.create',1553752888,1553752888,0),
  (6,'编辑权限页面','编辑权限页面',3,'','admin.permissions.edit',1553752888,1553752888,0),
  (7,'存储权限','存储权限',3,'','admin.permissions.store',1553752888,1553752888,0),
  (9,'删除权限','删除权限',3,'','admin.permissions.destroy',1553752888,1553752888,0),
  (10,'更新权限','更新权限',3,'','admin.permissions.update',1553752888,1553752888,0),
  (12,'编辑角色页面','编辑角色页面',2,'','admin.roles.edit',1553752888,1553752888,0),
  (13,'删除角色','删除角色',2,'','admin.roles.destroy',1553752888,1553752888,0),
  (14,'修改角色','修改角色权限',2,'','admin.roles.update',1553752888,1553752888,0),
  (15,'创建角色页面','创建角色页面',2,'','admin.roles.create',1553752888,1553752888,0),
  (16,'存储角色','存储角色',2,'','admin.roles.store',1553752888,1553752888,0),
  (18,'添加管理员页面','添加管理员页面',4,'','admin.admins.create',1553752888,1553752888,0),
  (19,'创建管理员','创建管理员',4,'','admin.admins.store',1553752888,1553752888,0),
  (20,'编辑管理员信息页面','编辑管理员信息页面',4,'','admin.admins.edit',1553752888,1553752888,0),
  (21,'更新管理员信息','更新管理员信息',4,'','admin.admins.update',1553752888,1553752888,0),
  (22,'删除管理员','删除管理员',4,'','admin.admins.destroy',1553752888,1553752888,0);

UNLOCK TABLES;