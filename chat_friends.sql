create table friends
(
    id        int auto_increment,
    user_id   int     default 0 not null,
    friend_id int     default 0 not null,
    status    tinyint default 1 null,
    constraint friend_id_uindex
        unique (id)
);

alter table friends
    add primary key (id);

INSERT INTO chat.friends (id, user_id, friend_id, status) VALUES (17, 8, 7, 1);
INSERT INTO chat.friends (id, user_id, friend_id, status) VALUES (18, 7, 8, 1);
INSERT INTO chat.friends (id, user_id, friend_id, status) VALUES (19, 8, 2, 1);
INSERT INTO chat.friends (id, user_id, friend_id, status) VALUES (20, 2, 8, 1);
INSERT INTO chat.friends (id, user_id, friend_id, status) VALUES (21, 7, 2, 1);
INSERT INTO chat.friends (id, user_id, friend_id, status) VALUES (22, 2, 7, 1);