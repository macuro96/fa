#!/bin/sh
sudo -u postgres psql -h localhost -U filmaff -d fa < fa.sql
