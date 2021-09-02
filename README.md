# email-importer

We integrate email providers like Gmail, Outlook, Yahoo for syncing user's emails. We need to create a command for launching such import daily for every user in database. Assume we store "user — email provider" tokens in database and we don't need to refresh them in this task, and we have low-level SDKs for GMail, Outlook and Yahoo. For simplicity, let's assume that they have same methods (but new email providers can have different methods):
- getEmails($offset, $limit) for getting email ids
- getEmailDetail() for getting email details as associative arrays, f.e. "sender", "recipients", "cc", "bcc", "subject", "body". Every provider may have different structure of this array and different field names.

During import, we also should parse email messages:
- extract data "sender" and "recipients" (fields in response of getEmailDetail() method) and store to database
- remove previous letters in email body (if user replies to another email, previous letter will be included) and store to database — every email provider has its own reply delimiter, let's assume ">>>>>" in GMail, "=====" in Outlook and "<<<<<<" in Yahoo. So, we just need to delete all what lies below.
