create table users
(
    id          int auto_increment,
    username    varchar(64)       not null,
    password    varchar(64)       not null,
    create_time int               not null,
    status      tinyint default 1 not null comment '1 正常 0 禁用',
    update_time int               not null,
    constraint users_id_uindex
        unique (id),
    constraint users_username_uindex
        unique (username)
)
    comment '用户表';

alter table users
    add primary key (id);

INSERT INTO chat.users (id, username, password, create_time, status, update_time) VALUES (2, 'test', '14e1b600b1fd579f47433b88e8d85291', 1629534698, 1, 1629534698);
INSERT INTO chat.users (id, username, password, create_time, status, update_time) VALUES (7, 'test2', '14e1b600b1fd579f47433b88e8d85291', 1629968979, 1, 1629968979);
INSERT INTO chat.users (id, username, password, create_time, status, update_time) VALUES (8, 'test3', '14e1b600b1fd579f47433b88e8d85291', 1629969003, 1, 1629969003);