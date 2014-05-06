# Haze - PassWORD generator

Passwords are pretty easy to remember already, I mean what kind of idiot wouldn't remember "kj4|89njknkjnsd,mms+__kjhfnbsd98", but this should make things a bit easier to remember how to get into your things.

## Sample Password

test-strain-clasp

The next word is taken from a pool of words based on the previous one, so all the words are related in some way and are somewhat more memorable.  A separator, the dash in this example, is optional and defaults to an empty string.

## Required API access

To run this code you'll need to sign up for an API account at [Big Huge Labs](https://words.bighugelabs.com/).  They're free, but you're limited to 1000 requests per day.  I've already hit the limit during development so you'll likely hit it as well, unless you pay for a higher tier with more daily requests.

Please note, the length of the word limits how many requests you can make.  If you want a 10 word password each time, at 1000 requests/day, you can only make 100 requests before you're rate limited. 

## Configuration

Rename `lib/api-sample.php` to `lib/api.php` and add your API key from Big Huge Labs.