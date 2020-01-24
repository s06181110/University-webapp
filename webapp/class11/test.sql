
create table list (
    id integer primary key autoincrement,
    url text,
    contents text
);

insert into list (url, contents) values ('http://hoge/', 'test contents');
select * from list;