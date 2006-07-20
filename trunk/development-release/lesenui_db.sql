create table lesen_comments
(
	id smallint(3) unsigned not null unique auto_increment, 
	author varchar(50),
	email varchar(100),
	url varchar(200),
	body text not null,
	timestamp datetime not null,
	paper_id smallint(4) unsigned not null,
	parent_id smallint(3) unsigned,
	primary key (id),
	foreign key (parent_id) references lesen_comments(id),
	foreign key (paper_id) references paper(code)
);