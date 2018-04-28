# tt-rss_sinfest
TT-RSS Plugin: Fetch today's comic from Sinfest

This is a simplified hackup of af_comics to generate a feed for Sinfest (http://sinfest.net/) comics.

It is hacky and probably could be redone better, but for 15 minutes work it is fine. Pull requests welcomed.

# Usage

Enable the plugin, then subscribe to http://sinfest.net/ . The plugin will automatically fetch the day's comic and embed it. (The feed title is [Unknown] for reasons that I can't be bothered to dig into today. Just rename it.)

There are 2 main features missing (beyond the title bug):
- No history. All of the comics are available on the website, so it should be possible to fetch a few days at a time. In the meantime, just keep your updater daemon running..
- No alt text. I might fix that someday, but if it bothers you please send a pull request.

