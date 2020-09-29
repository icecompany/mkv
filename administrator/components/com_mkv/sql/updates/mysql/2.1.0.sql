create table `#__mkv_history`
(
    id        int unsigned auto_increment not null primary key,
    dat       timestamp                   not null default current_timestamp,
    managerID int                         not null,
    itemID    int unsigned                not null,
    section   set ('contract', 'cart')    not null,
    action    set ('add', 'update', 'delete'),
    old_data  text                        null     default null,
    new_data  text                        null     default null,
    index `#__mkv_history_dat_index` (dat),
    index `#__mkv_history_managerID_section_dat_index` (managerID, section, dat),
    index `#__mkv_history_section_index` (section)
) character set utf8
  collate utf8_general_ci;