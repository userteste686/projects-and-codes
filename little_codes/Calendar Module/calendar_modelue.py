#https://www.hackerrank.com/challenges/calendar-module/problem
import calendar as c

def data():
    month , day , year = map(int,input().split())
    weekday_index = c.weekday(year , month , day)
    weekday_name = c.day_name[weekday_index]
    print(weekday_name.upper())
    
data()
