import serial
import sys
import time

data = sys.stdin.buffer.read()
lines = data.splitlines()

s = serial.Serial('/dev/ttyUSB0', 9600)
for line in lines:
    s.write(line + b'\r\n')

    while s.in_waiting:
        s.readline()

    time.sleep(0.5)