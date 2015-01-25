When specific events occur, Gustav writes helpful messages to log files. The log files are located in the a directory called `logs` which is located in the same directory as the PHP files defining the [Gustav classes](API#gustav-classes). The main log file is named `gustav.log` and is renamed and archived when its filesize exceeds [the limit](Gustav-configuration#stringint-log_file_max_size---1).



##Log entries

Log entries are separated by an empty line.  
A typical log entry looks as follows.

    WARNING    Mon, 19 Jan 2015 12:53:00 +0100
    This is a log entry.

`WARNING` may vary and can also be `ERROR` or `SUCCESS`.