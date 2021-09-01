create table chats
(
    id          int auto_increment,
    user_id     int default 0 not null,
    friend_id   int default 0 not null,
    message     text          not null,
    create_time int default 0 not null,
    update_time int default 0 not null,
    constraint chat_id_uindex
        unique (id)
)
    comment '聊天记录';
