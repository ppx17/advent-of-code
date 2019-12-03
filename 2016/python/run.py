#!/usr/bin/env python3

import argparse
import sys

parser = argparse.ArgumentParser(
    prog='aoc run',
    description='Runs submissions')

parser.add_argument("-d", "--day", help="problem day", action="append")

args = parser.parse_args(sys.argv)

