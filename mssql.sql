select t1.VISIT, t1.MIDSERVER, t1.nationalsum , t1.CLOSEDATETIME, t2.prlistsum, t2.QUANTITY, menu.NAME, cash.NAME
from  [RK7].[dbo].[PRINTCHECKS] as t1,
[RK7].[dbo].SESSIONDISHES as t2,
[RK7].[dbo].MENUITEMS as menu,
[RK7].[dbo].CASHES as cash
where t1.VISIT = t2.VISIT
and t1.MIDSERVER = t2.MIDSERVER
and menu.SIFR = t2.SIFR
and cash.sifr = t1.IPRINTSTATION
and convert(date, t1.CLOSEDATETIME) > convert(date,getdate()-7);


-- stats for 8 last days
select sum(t1.nationalsum) as cash, count(t1.nationalsum) as checks , convert(date, t1.CLOSEDATETIME) as date
from  [RK7].[dbo].[PRINTCHECKS] as t1,
[RK7].[dbo].CASHES as cash
where cash.sifr = t1.IPRINTSTATION
and convert(date, t1.CLOSEDATETIME) > convert(date,getdate()-8)
and  (cash.NAME = 'Б60 Касса' or cash.Name = 'Б60 Касса 2')
group by convert(date, t1.CLOSEDATETIME)
order by date;

-- stats for 3 last months
select sum(t1.nationalsum) as cash, count(t1.nationalsum) as checks , month(t1.CLOSEDATETIME) as date
from  [RK7].[dbo].[PRINTCHECKS] as t1,
[RK7].[dbo].CASHES as cash
where cash.sifr = t1.IPRINTSTATION
and year(t1.CLOSEDATETIME) = year(getdate())
and month(t1.CLOSEDATETIME) >= month(getdate())-2
 and cash.NAME LIKE '%Б%'
 and cash.NAME LIKE '%73%'
 and cash.NAME LIKE '%Касса%'
-- and (cash.NAME = 'Б60 Касса' or cash.Name = 'Б60 Касса 2')
group by month(t1.CLOSEDATETIME)
order by date;
