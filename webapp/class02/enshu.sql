-- [1]
create table seiseki(
    id integer primary key autoincrement,
    name text,
    kogi text,
    point integer
);

insert into seiseki values (1,   'user1', 'Python', 100);
insert into seiseki values (2,   'user1',   'Java',  90);
insert into seiseki values (3,   'user2', 'Python',  80);
insert into seiseki values (4,   'user2',   'Java',  60);
insert into seiseki values (5, 'ayamoto',    'SE2',   0);

-- [2]
select * from seiseki;
select name, kogi from seiseki;
select kogi, id, name, point from seiseki;

-- [3]
select distinct kogi from seiseki;
select * from seiseki where point >= 80 and point < 90;

-- [4]
select * from seiseki order by point desc;

-- [5]
select kogi, avg(point) from seiseki group by kogi;

-- [6]
select name, avg(point) from seiseki group by name having avg(point) >= 60;

-- [7]
select name, avg(point) from seiseki group by name order by avg(point) desc limit 2;
