#!/bin/bash
cd /home/kit-auto-fid-generator && php runner.php && node RPAExport && echo RPAExport done > cron.log
