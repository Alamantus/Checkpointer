# Checkpointer
A goal manager with infinitely nested checkpoints.

## Requirements

- Server with PHP
- Support for PHP PDO SQLite3

## Installation

Upload the files.

Modify `config.php`:

- If you keep `ENCRYPT_DATA` set to `true` to obscure data entered into the database, then create a key and IV pair.
  - If you set `ENCRYPT_DATA` to `false`, then it will skip the encryption step.
  - _**Warning!**_ Changing `ENCRYPT_DATA`'s value after entering data will cause problems like making the data unusable, so don't do it!
- Set `TIMEZONE` to your [PHP timezone](http://php.net/manual/en/timezones.php), otherwise it will display UTC time.

## Usage

Create an account, log in with the account, and click "New Goal" to create a new top-level checkpoint (a "goal").

To create checkpoints within that goal, click the `+` next to the title and create it. You can also do this on checkpoints themselves to create checkpoints within the checkpoints.

You can move checkpoints to change their order or to move them to inside of a different checkpoint (if the target checkpoint's checkpoints are not hidden) by clicking and dragging on the `⇅` in the top left corner of the checkpoint.

You can edit checkpoints by clicking the `Edit` button next to the title. From within the edit form, you can delete the checkpoint and all its children by clicking the `Delete?` button in the bottom right corner of the form.

If you Edit a Goal, you can set its privacy. Making it public will make it visible to the public for anyone who visits http://<checkpointer installation>/?user=<your username> (eg. http://localhost/checkpointer/?user=george).

You can set the status of a checkpoint by using the dropdown box in the top left corner of the checkpoint. Hovering over the options will display the intended purpose of the icon, but only `●` ("Complete") and `✘` ("Canceled") affect the way the checkpoint and its children display. _Note: Setting a checkpoint to Complete or Canceled will set all of its childrens' status to the same status, but changing it back to one of the other statuses will **not** update the children again._

Note that while you can technically achieve infinitely deep nesting of checkpoints, the display will probably not accomodate that for very long—the deeper an item is nested, the larger its left-side margin becomes, shrinking the checkpoint's width.
