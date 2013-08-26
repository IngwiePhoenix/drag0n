#!/bin/bash
# A quick "how to fix your shit":

# 1st: we need a dumb terminal to avoid pesky stderr messages:
export TERM=dumb

# 2nd: Our entire cmd is in a variable we need to EVALUATE.
eval "$THE_ARGS"

# 3nd: We need to clean the mess we started.
export THE_ARGS=""