create table member(
    id integer,
    name text,
    age integer,
    section text
);

insert into member values (01,  'tanaka', 57, 'eigyo');
insert into member values (02,  'tanaka', 57, 'se');
insert into member values (03,  'suzuki', 42, 'eigyo');
insert into member values (04,  'suzuki', 42, 'se');
insert into member values (05,  'ikeda', 20, 'koho');
insert into member values (06,  'ikeda', 20, 'jinji');
insert into member values (07,  'ikeda', 20, 'programmer');
insert into member values (08,  'ikeda', 20, 'se');
insert into member values (09,  'enomoto', 30, 'eigyo');
insert into member values (10,  'enomoto', 30, 'se');
insert into member values (11,  'enomoto', 30, 'programmer');
insert into member values (12,  'arai', 28, 'se');
insert into member values (13,  'arai', 28, 'cto');
insert into member values (14,  'arai', 28, 'programmer');
insert into member values (16,  'yamamoto', 60, 'ceo');
insert into member values (17,  'mori', 50, 'programmer');
insert into member values (18,  'mori', 50, 'se');
insert into member values (19,  'mori', 50, 'sequrity');
insert into member values (20,  'mori', 50, 'jinji');

select * from member;

select section, name, age from member;

select DISTINCT name from member;

select * from member where age>=30 and age<60;

select * from member order by age DESC;

select * from member ORDER BY age LIMIT 3;

select DISTINCT name from member ORDER BY age;

select section, avg(age) from member group by section having age<=50;

select * from member where name like '%i%';