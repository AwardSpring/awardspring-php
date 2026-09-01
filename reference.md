# Reference
## Award Cycles
<details><summary><code>$client-&gt;awardCycles-&gt;list($request) -> ?AwardCycleV1ListResponse</code></summary>
<dl>
<dd>

#### 📝 Description

<dl>
<dd>

<dl>
<dd>

Returns the award cycles belonging to the institution your API key is issued for, in the list
envelope (`object: "list"`, `has_more`, `next_cursor`, `previous_cursor`,
`data`). Page forward by passing the returned `next_cursor` as `starting_after`;
the last page has `next_cursor: null`. `limit` defaults to 25 and is capped at 100.
Results are ordered by award-cycle id ascending. Filter with `?q=` (case-insensitive
substring match on the award-cycle name).
</dd>
</dl>
</dd>
</dl>

#### 🔌 Usage

<dl>
<dd>

<dl>
<dd>

```php
$client->awardCycles->list(
    new ListAwardCyclesRequest([]),
);
```
</dd>
</dl>
</dd>
</dl>

#### ⚙️ Parameters

<dl>
<dd>

<dl>
<dd>

**$q:** `?string` 

Free-text search (`?q=`). Case-insensitive substring match against the award-cycle
name. Null/blank means no name filter.
    
</dd>
</dl>

<dl>
<dd>

**$limit:** `?int` — Page size. Defaults to 25. Values outside 1–100 are clamped rather than rejected.
    
</dd>
</dl>

<dl>
<dd>

**$startingAfter:** `?string` — Opaque cursor. Pass the `next_cursor` from the previous page to page forward. Mutually exclusive with `ending_before`; if both are supplied, `starting_after` wins.
    
</dd>
</dl>

<dl>
<dd>

**$endingBefore:** `?string` — Opaque cursor. Pass the `previous_cursor` from the current page to page backward.
    
</dd>
</dl>
</dd>
</dl>


</dd>
</dl>
</details>

<details><summary><code>$client-&gt;awardCycles-&gt;getCurrent() -> ?AwardCycleV1</code></summary>
<dl>
<dd>

#### 📝 Description

<dl>
<dd>

<dl>
<dd>

Returns a single AwardCycleV1 resource. The lookup prefers the cycle the
institution has marked current; when none is marked current it falls back to the cycle
marked next. When neither exists the endpoint returns `404 award_cycle_not_found` in the
structured v1 error shape — in that case list all cycles via `GET /api/v1/award-cycles`
and ask the user which one to use.
</dd>
</dl>
</dd>
</dl>

#### 🔌 Usage

<dl>
<dd>

<dl>
<dd>

```php
$client->awardCycles->getCurrent();
```
</dd>
</dl>
</dd>
</dl>


</dd>
</dl>
</details>

## Donor Activities
<details><summary><code>$client-&gt;donorActivities-&gt;list($donorId, $request) -> ?DonorActivityV1ListResponse</code></summary>
<dl>
<dd>

#### 🔌 Usage

<dl>
<dd>

<dl>
<dd>

```php
$client->donorActivities->list(
    1,
    new ListDonorActivitiesRequest([]),
);
```
</dd>
</dl>
</dd>
</dl>

#### ⚙️ Parameters

<dl>
<dd>

<dl>
<dd>

**$donorId:** `int` 
    
</dd>
</dl>

<dl>
<dd>

**$q:** `?string` — Free-text search (`?q=`): case-insensitive substring match on subject or description.
    
</dd>
</dl>

<dl>
<dd>

**$activityType:** `?string` — Activity-type filter (`?activity_type=`): case-insensitive exact match on the activity type label.
    
</dd>
</dl>

<dl>
<dd>

**$limit:** `?int` — Page size. Defaults to 25. Values outside 1–100 are clamped rather than rejected.
    
</dd>
</dl>

<dl>
<dd>

**$startingAfter:** `?string` — Opaque cursor. Pass the `next_cursor` from the previous page to page forward. Mutually exclusive with `ending_before`; if both are supplied, `starting_after` wins.
    
</dd>
</dl>

<dl>
<dd>

**$endingBefore:** `?string` — Opaque cursor. Pass the `previous_cursor` from the current page to page backward.
    
</dd>
</dl>
</dd>
</dl>


</dd>
</dl>
</details>

<details><summary><code>$client-&gt;donorActivities-&gt;create($donorId, $request) -> ?CreateDonorActivityV1Response</code></summary>
<dl>
<dd>

#### 📝 Description

<dl>
<dd>

<dl>
<dd>

Records that an interaction with a donor took place — a call you just finished, a meeting, an
email, a note, or a verbal pledge. Errors come back with a stable `code`, a readable
`message`, and per-field `details`, so a caller can tell what to correct.


Supported activity types: `LoggedEmail`, `LoggedPhone`, `LoggedMeeting`, `LoggedNote`,
`LoggedPledge`. Gifts are recorded through the Gifts endpoints instead.


A typical use is working through a call list and logging each conversation as it ends, with the
summary and any follow-up owner attached.


Set `dry_run=true` (query param or `Dry-Run: true` header) to validate without persisting.

<b>Idempotency:</b> the request supports an optional `Idempotency-Key` header (any printable-ASCII
string, 1–255 chars — UUIDs work well). When supplied, the same key + same request body within 24 hours
returns the original response verbatim without creating a duplicate activity. The same key with a
different body returns `422 idempotency_key_request_mismatch`; a concurrent retry while the first
call is still executing returns `409 idempotency_key_in_flight`. Only 2xx responses are cached —
4xx/5xx leave the key available for retry with a corrected payload.
</dd>
</dl>
</dd>
</dl>

#### 🔌 Usage

<dl>
<dd>

<dl>
<dd>

```php
$client->donorActivities->create(
    1,
    new CreateDonorActivityV1Request([]),
);
```
</dd>
</dl>
</dd>
</dl>

#### ⚙️ Parameters

<dl>
<dd>

<dl>
<dd>

**$donorId:** `int` — AwardSpring donor (or donor-organization) user ID to attach the activity to.
    
</dd>
</dl>

<dl>
<dd>

**$activityType:** `?string` 

The kind of activity being logged. Supported values:
<list type="bullet"><item><description>`LoggedEmail` — an email exchanged with the donor (manually logged after the fact). Example: `"LoggedEmail"`.</description></item><item><description>`LoggedPhone` — a phone conversation with the donor. Example: `"LoggedPhone"`.</description></item><item><description>`LoggedMeeting` — an in-person or virtual meeting. Example: `"LoggedMeeting"`.</description></item><item><description>`LoggedNote` — a free-form note attached to the donor record. Example: `"LoggedNote"`.</description></item><item><description>`LoggedPledge` — a recorded pledge of a future gift; requires Amount. Example: `"LoggedPledge"`.</description></item></list>
Gifts (`LoggedGift`) are not supported by this endpoint — they are recorded through a separate gift-entry flow.
    
</dd>
</dl>

<dl>
<dd>

**$activityDate:** `?string` 

ISO 8601 date or date-time the activity occurred (or is scheduled for, when in the future). A full
date is required — examples: `"2026-04-03"`, `"2026-04-03T10:00:00Z"`, `"2026-04-03T10:00:00-05:00"`.
Time-of-day is optional. The date is interpreted in the tenant's configured time zone and persisted in UTC.
    
</dd>
</dl>

<dl>
<dd>

**$subject:** `?string` 

Short human-readable title for the activity. Required, non-whitespace. Example: `"Quarterly stewardship call"`.
Surfaced in donor activity lists in the AwardSpring admin UI.
    
</dd>
</dl>

<dl>
<dd>

**$description:** `?string` — Optional free-form details about the activity. Example: `"Discussed plans for the spring scholarship gala; donor expressed interest in funding a new STEM award."`.
    
</dd>
</dl>

<dl>
<dd>

**$amount:** `?float` 

Monetary amount for pledge activities. Required and greater than zero when
`activity_type` is `LoggedPledge`; ignored for non-monetary activity
types. Example: `5000.00`.
    
</dd>
</dl>

<dl>
<dd>

**$fundId:** `?string` 

Optional fund identifier the pledge is directed toward. Only meaningful when ActivityType
is `LoggedPledge`. When the tenant has Funds Management enabled, the value must match an existing
fund's `FundIdName`; otherwise a `fund_not_found` error is returned. When Funds Management
is disabled, the value is stored as a free-form label without validation. Example: `"GEN-2026"`.
    
</dd>
</dl>

<dl>
<dd>

**$campaignId:** `?int` 

Optional campaign association for pledge activities. Only meaningful when ActivityType
is `LoggedPledge`; ignored for non-monetary activity types. Example: `42`.
    
</dd>
</dl>

<dl>
<dd>

**$assignedToUserId:** `?int` 

Optional AwardSpring user ID of the staff member the activity is assigned to (e.g., the donor manager
who should follow up). Pass `null` or omit when no assignment is intended. Example: `12`.
    
</dd>
</dl>

<dl>
<dd>

**$isCompleted:** `?bool` 

Whether the activity is completed. For non-monetary activity types this is ignored (treated as `false`).
For `LoggedPledge`, `true` indicates the pledge has been fulfilled. Defaults to `false`.
    
</dd>
</dl>
</dd>
</dl>


</dd>
</dl>
</details>

<details><summary><code>$client-&gt;donorActivities-&gt;get($donorId, $activityId, $request) -> ?DonorActivityV1</code></summary>
<dl>
<dd>

#### 🔌 Usage

<dl>
<dd>

<dl>
<dd>

```php
$client->donorActivities->get(
    1,
    1,
    new GetDonorActivitiesRequest([]),
);
```
</dd>
</dl>
</dd>
</dl>

#### ⚙️ Parameters

<dl>
<dd>

<dl>
<dd>

**$donorId:** `int` 
    
</dd>
</dl>

<dl>
<dd>

**$activityId:** `int` 
    
</dd>
</dl>

<dl>
<dd>

**$source:** `?string` 
    
</dd>
</dl>
</dd>
</dl>


</dd>
</dl>
</details>

## Donors
<details><summary><code>$client-&gt;donors-&gt;list($request) -> ?DonorListItemV1ListResponse</code></summary>
<dl>
<dd>

#### 📝 Description

<dl>
<dd>

<dl>
<dd>

Returns one page at a time. Search with `q`: it splits on spaces and matches each
term against first name, last name, email, or organization, so `jane smith` finds
donors matching both terms.
</dd>
</dl>
</dd>
</dl>

#### 🔌 Usage

<dl>
<dd>

<dl>
<dd>

```php
$client->donors->list(
    new ListDonorsRequest([]),
);
```
</dd>
</dl>
</dd>
</dl>

#### ⚙️ Parameters

<dl>
<dd>

<dl>
<dd>

**$q:** `?string` 
    
</dd>
</dl>

<dl>
<dd>

**$limit:** `?int` — Page size. Defaults to 25. Values outside 1–100 are clamped rather than rejected.
    
</dd>
</dl>

<dl>
<dd>

**$startingAfter:** `?string` — Opaque cursor. Pass the `next_cursor` from the previous page to page forward. Mutually exclusive with `ending_before`; if both are supplied, `starting_after` wins.
    
</dd>
</dl>

<dl>
<dd>

**$endingBefore:** `?string` — Opaque cursor. Pass the `previous_cursor` from the current page to page backward.
    
</dd>
</dl>
</dd>
</dl>


</dd>
</dl>
</details>

<details><summary><code>$client-&gt;donors-&gt;create($request) -> ?DonorV1</code></summary>
<dl>
<dd>

#### 📝 Description

<dl>
<dd>

<dl>
<dd>

Creates either an individual or an organization, depending on `role`. Send
`dry_run` to validate the request without saving it, and an `Idempotency-Key`
header so a retry cannot create the same donor twice.
</dd>
</dl>
</dd>
</dl>

#### 🔌 Usage

<dl>
<dd>

<dl>
<dd>

```php
$client->donors->create(
    new CreateDonorV1Request([]),
);
```
</dd>
</dl>
</dd>
</dl>

#### ⚙️ Parameters

<dl>
<dd>

<dl>
<dd>

**$email:** `?string` 

Optional email address — donors may have no email on file, matching the admin UI. When
provided it must be a valid email format, 250 characters or fewer, and not already in
use by another user in the institution. Note that email-less creates get no duplicate
protection from that uniqueness check, so retried requests should carry an
`Idempotency-Key` header to avoid creating the same donor twice.
    
</dd>
</dl>

<dl>
<dd>

**$firstName:** `?string` 
    
</dd>
</dl>

<dl>
<dd>

**$lastName:** `?string` 
    
</dd>
</dl>

<dl>
<dd>

**$phone:** `?string` 
    
</dd>
</dl>

<dl>
<dd>

**$organization:** `?string` 
    
</dd>
</dl>

<dl>
<dd>

**$role:** `?string` 

Whether this is an individual donor or a donor organization. Defaults to
`Individual` when omitted. Determines which name fields are required.
    
</dd>
</dl>

<dl>
<dd>

**$address1:** `?string` 
    
</dd>
</dl>

<dl>
<dd>

**$address2:** `?string` 
    
</dd>
</dl>

<dl>
<dd>

**$city:** `?string` 
    
</dd>
</dl>

<dl>
<dd>

**$state:** `?string` 
    
</dd>
</dl>

<dl>
<dd>

**$county:** `?string` 
    
</dd>
</dl>

<dl>
<dd>

**$zipCode:** `?string` 
    
</dd>
</dl>

<dl>
<dd>

**$country:** `?int` 
    
</dd>
</dl>

<dl>
<dd>

**$jobTitle:** `?string` 
    
</dd>
</dl>

<dl>
<dd>

**$workEmail:** `?string` 
    
</dd>
</dl>

<dl>
<dd>

**$workPhone:** `?string` 
    
</dd>
</dl>

<dl>
<dd>

**$companyName:** `?string` 
    
</dd>
</dl>

<dl>
<dd>

**$publicFirstName:** `?string` 
    
</dd>
</dl>

<dl>
<dd>

**$publicLastName:** `?string` 
    
</dd>
</dl>

<dl>
<dd>

**$publicOrganizationName:** `?string` 
    
</dd>
</dl>

<dl>
<dd>

**$website:** `?string` 
    
</dd>
</dl>

<dl>
<dd>

**$facebookUrl:** `?string` 
    
</dd>
</dl>

<dl>
<dd>

**$twitterUrl:** `?string` 
    
</dd>
</dl>

<dl>
<dd>

**$linkedInUrl:** `?string` 
    
</dd>
</dl>

<dl>
<dd>

**$description:** `?string` 
    
</dd>
</dl>

<dl>
<dd>

**$notes:** `?string` 
    
</dd>
</dl>

<dl>
<dd>

**$makeProfilePrivate:** `?bool` 
    
</dd>
</dl>

<dl>
<dd>

**$includeSoftCredits:** `?bool` 
    
</dd>
</dl>
</dd>
</dl>


</dd>
</dl>
</details>

<details><summary><code>$client-&gt;donors-&gt;get($id) -> ?DonorDetailV1</code></summary>
<dl>
<dd>

#### 📝 Description

<dl>
<dd>

<dl>
<dd>

Includes giving totals for the donor's lifetime and the current year, their most recent
gift, and their notes. Returns `404 donor_not_found` if no such donor belongs to
this institution.
</dd>
</dl>
</dd>
</dl>

#### 🔌 Usage

<dl>
<dd>

<dl>
<dd>

```php
$client->donors->get(
    1,
);
```
</dd>
</dl>
</dd>
</dl>

#### ⚙️ Parameters

<dl>
<dd>

<dl>
<dd>

**$id:** `int` 
    
</dd>
</dl>
</dd>
</dl>


</dd>
</dl>
</details>

<details><summary><code>$client-&gt;donors-&gt;update($id, $request) -> ?DonorV1</code></summary>
<dl>
<dd>

#### 📝 Description

<dl>
<dd>

<dl>
<dd>

Only the fields present in the request body change; anything omitted is left alone. Send
`dry_run` to validate the request without saving it.
</dd>
</dl>
</dd>
</dl>

#### 🔌 Usage

<dl>
<dd>

<dl>
<dd>

```php
$client->donors->update(
    1,
    new UpdateDonorV1Request([]),
);
```
</dd>
</dl>
</dd>
</dl>

#### ⚙️ Parameters

<dl>
<dd>

<dl>
<dd>

**$id:** `int` 
    
</dd>
</dl>

<dl>
<dd>

**$email:** `?string` 
    
</dd>
</dl>

<dl>
<dd>

**$firstName:** `?string` 
    
</dd>
</dl>

<dl>
<dd>

**$lastName:** `?string` 
    
</dd>
</dl>

<dl>
<dd>

**$phone:** `?string` 
    
</dd>
</dl>

<dl>
<dd>

**$organization:** `?string` 
    
</dd>
</dl>

<dl>
<dd>

**$address1:** `?string` 
    
</dd>
</dl>

<dl>
<dd>

**$address2:** `?string` 
    
</dd>
</dl>

<dl>
<dd>

**$city:** `?string` 
    
</dd>
</dl>

<dl>
<dd>

**$state:** `?string` 
    
</dd>
</dl>

<dl>
<dd>

**$county:** `?string` 
    
</dd>
</dl>

<dl>
<dd>

**$zipCode:** `?string` 
    
</dd>
</dl>

<dl>
<dd>

**$country:** `?int` 
    
</dd>
</dl>

<dl>
<dd>

**$jobTitle:** `?string` 
    
</dd>
</dl>

<dl>
<dd>

**$workEmail:** `?string` 
    
</dd>
</dl>

<dl>
<dd>

**$workPhone:** `?string` 
    
</dd>
</dl>

<dl>
<dd>

**$companyName:** `?string` 
    
</dd>
</dl>

<dl>
<dd>

**$publicFirstName:** `?string` 
    
</dd>
</dl>

<dl>
<dd>

**$publicLastName:** `?string` 
    
</dd>
</dl>

<dl>
<dd>

**$publicOrganizationName:** `?string` 
    
</dd>
</dl>

<dl>
<dd>

**$website:** `?string` 
    
</dd>
</dl>

<dl>
<dd>

**$facebookUrl:** `?string` 
    
</dd>
</dl>

<dl>
<dd>

**$twitterUrl:** `?string` 
    
</dd>
</dl>

<dl>
<dd>

**$linkedInUrl:** `?string` 
    
</dd>
</dl>

<dl>
<dd>

**$description:** `?string` 
    
</dd>
</dl>

<dl>
<dd>

**$notes:** `?string` 
    
</dd>
</dl>

<dl>
<dd>

**$makeProfilePrivate:** `?bool` 
    
</dd>
</dl>

<dl>
<dd>

**$includeSoftCredits:** `?bool` 
    
</dd>
</dl>
</dd>
</dl>


</dd>
</dl>
</details>

<details><summary><code>$client-&gt;donors-&gt;updateNotes($id, $request) -> ?DonorNotesV1</code></summary>
<dl>
<dd>

#### 📝 Description

<dl>
<dd>

<dl>
<dd>

Replaces the donor's free-text notes. Returns `404 donor_not_found` if no such donor
belongs to this institution.
</dd>
</dl>
</dd>
</dl>

#### 🔌 Usage

<dl>
<dd>

<dl>
<dd>

```php
$client->donors->updateNotes(
    1,
    new UpdateDonorNotesV1Request([]),
);
```
</dd>
</dl>
</dd>
</dl>

#### ⚙️ Parameters

<dl>
<dd>

<dl>
<dd>

**$id:** `int` 
    
</dd>
</dl>

<dl>
<dd>

**$notes:** `?string` 
    
</dd>
</dl>
</dd>
</dl>


</dd>
</dl>
</details>

## Funds
<details><summary><code>$client-&gt;funds-&gt;list($request) -> ?FundListItemV1ListResponse</code></summary>
<dl>
<dd>

#### 📝 Description

<dl>
<dd>

<dl>
<dd>

Returns the funds belonging to the institution your API key is issued for, in the standard list envelope
(`object: "list"`, `has_more`, `next_cursor`, `previous_cursor`,
`data`). Page forward by passing the returned `next_cursor` as
`starting_after`. Filter with `?q=` (case-insensitive substring match on the
fund name).
</dd>
</dl>
</dd>
</dl>

#### 🔌 Usage

<dl>
<dd>

<dl>
<dd>

```php
$client->funds->list(
    new ListFundsRequest([]),
);
```
</dd>
</dl>
</dd>
</dl>

#### ⚙️ Parameters

<dl>
<dd>

<dl>
<dd>

**$q:** `?string` 
    
</dd>
</dl>

<dl>
<dd>

**$limit:** `?int` — Page size. Defaults to 25. Values outside 1–100 are clamped rather than rejected.
    
</dd>
</dl>

<dl>
<dd>

**$startingAfter:** `?string` — Opaque cursor. Pass the `next_cursor` from the previous page to page forward. Mutually exclusive with `ending_before`; if both are supplied, `starting_after` wins.
    
</dd>
</dl>

<dl>
<dd>

**$endingBefore:** `?string` — Opaque cursor. Pass the `previous_cursor` from the current page to page backward.
    
</dd>
</dl>
</dd>
</dl>


</dd>
</dl>
</details>

## Gifts
<details><summary><code>$client-&gt;gifts-&gt;list($request) -> ?GiftV1ListResponse</code></summary>
<dl>
<dd>

#### 📝 Description

<dl>
<dd>

<dl>
<dd>

Returns one page at a time, newest first. Without filters the list covers the whole
institution. Narrow it with `donor_id` to scope to a single donor, `type` to
return only gifts or only pledges, and `q` to search the subject and description.
</dd>
</dl>
</dd>
</dl>

#### 🔌 Usage

<dl>
<dd>

<dl>
<dd>

```php
$client->gifts->list(
    new ListGiftsRequest([]),
);
```
</dd>
</dl>
</dd>
</dl>

#### ⚙️ Parameters

<dl>
<dd>

<dl>
<dd>

**$donorId:** `?int` — Optional donor scope (`?donor_id=`). Omit for a tenant-wide list.
    
</dd>
</dl>

<dl>
<dd>

**$type:** `?string` — Record-type filter (`?type=`): `gift` or `pledge`. Omit for both.
    
</dd>
</dl>

<dl>
<dd>

**$q:** `?string` — Free-text search (`?q=`): case-insensitive substring match on subject or description.
    
</dd>
</dl>

<dl>
<dd>

**$limit:** `?int` — Page size. Defaults to 25. Values outside 1–100 are clamped rather than rejected.
    
</dd>
</dl>

<dl>
<dd>

**$startingAfter:** `?string` — Opaque cursor. Pass the `next_cursor` from the previous page to page forward. Mutually exclusive with `ending_before`; if both are supplied, `starting_after` wins.
    
</dd>
</dl>

<dl>
<dd>

**$endingBefore:** `?string` — Opaque cursor. Pass the `previous_cursor` from the current page to page backward.
    
</dd>
</dl>
</dd>
</dl>


</dd>
</dl>
</details>

<details><summary><code>$client-&gt;gifts-&gt;create($request) -> ?GiftV1</code></summary>
<dl>
<dd>

#### 📝 Description

<dl>
<dd>

<dl>
<dd>

Gifts may also carry soft credits, which acknowledge someone other than the giver.
Send `dry_run` to validate the request without saving it, and an
`Idempotency-Key` header so a retry cannot record the same gift twice.
</dd>
</dl>
</dd>
</dl>

#### 🔌 Usage

<dl>
<dd>

<dl>
<dd>

```php
$client->gifts->create(
    new CreateGiftV1Request([]),
);
```
</dd>
</dl>
</dd>
</dl>

#### ⚙️ Parameters

<dl>
<dd>

<dl>
<dd>

**$donorId:** `?int` — The donor (or donor organization) user id the gift is attributed to. Required.
    
</dd>
</dl>

<dl>
<dd>

**$type:** `?string` 

`gift` or `pledge`. Defaults to `gift` when omitted. Soft credits are only
            honored on gifts.
    
</dd>
</dl>

<dl>
<dd>

**$giftType:** `?string` 

Gift instrument — one of `Cash`, `Check`, `CreditOrDebit`, `BankTransfer`,
`StockOrProperty`, `InKind`, `PayrollDeduction`, `Online`, `Other`.
Optional; defaults to `None`. Ignored for pledges.
    
</dd>
</dl>

<dl>
<dd>

**$amount:** `?float` 

Monetary amount. Required for both gifts and pledges and must be greater than zero;
a zero amount is allowed only when GiftType is `InKind`.
    
</dd>
</dl>

<dl>
<dd>

**$subject:** `?string` — Short human-readable title. Required, non-whitespace.
    
</dd>
</dl>

<dl>
<dd>

**$description:** `?string` 
    
</dd>
</dl>

<dl>
<dd>

**$giftDate:** `?string` 

ISO 8601 date the gift/pledge occurred (e.g. `"2026-04-03"` or `"2026-04-03T10:00:00Z"`).
Required. Interpreted in the tenant's time zone and persisted in UTC.
    
</dd>
</dl>

<dl>
<dd>

**$fundId:** `?string` 

Fund the gift is directed toward. When the tenant has Funds Management enabled the value must
match an existing fund's identifier; otherwise it is stored as a free-form label.
    
</dd>
</dl>

<dl>
<dd>

**$campaignId:** `?int` 
    
</dd>
</dl>

<dl>
<dd>

**$isCompleted:** `?bool` — Only meaningful for pledges — marks the pledge fulfilled. Ignored for gifts.
    
</dd>
</dl>

<dl>
<dd>

**$assignedToUserId:** `?int` — Officer the record is assigned to. Must be a user in this tenant when supplied.
    
</dd>
</dl>

<dl>
<dd>

**$softCredits:** `?array` — Up to three soft-credit recipients. Honored on gifts only; ignored for pledges.
    
</dd>
</dl>
</dd>
</dl>


</dd>
</dl>
</details>

## Scholarships
<details><summary><code>$client-&gt;scholarships-&gt;list($request) -> ?ScholarshipListItemV1ListResponse</code></summary>
<dl>
<dd>

#### 📝 Description

<dl>
<dd>

<dl>
<dd>

Returns the scholarships belonging to the institution your API key is issued for, in the list
envelope (`object: "list"`, `has_more`, `next_cursor`, `previous_cursor`,
`data`). Page forward by passing the returned `next_cursor` as `starting_after`;
the last page has `next_cursor: null`. `limit` defaults to 25 and is capped at 100.
Results are ordered by scholarship id ascending.
Filter with `?q=` (case-insensitive substring match on the scholarship name).
</dd>
</dl>
</dd>
</dl>

#### 🔌 Usage

<dl>
<dd>

<dl>
<dd>

```php
$client->scholarships->list(
    new ListScholarshipsRequest([]),
);
```
</dd>
</dl>
</dd>
</dl>

#### ⚙️ Parameters

<dl>
<dd>

<dl>
<dd>

**$q:** `?string` 

Free-text search (`?q=`). Case-insensitive substring match against the scholarship
name. Null/blank means no name filter.
    
</dd>
</dl>

<dl>
<dd>

**$limit:** `?int` — Page size. Defaults to 25. Values outside 1–100 are clamped rather than rejected.
    
</dd>
</dl>

<dl>
<dd>

**$startingAfter:** `?string` — Opaque cursor. Pass the `next_cursor` from the previous page to page forward. Mutually exclusive with `ending_before`; if both are supplied, `starting_after` wins.
    
</dd>
</dl>

<dl>
<dd>

**$endingBefore:** `?string` — Opaque cursor. Pass the `previous_cursor` from the current page to page backward.
    
</dd>
</dl>
</dd>
</dl>


</dd>
</dl>
</details>

<details><summary><code>$client-&gt;scholarships-&gt;create($request) -> ?CreateScholarshipV1Response</code></summary>
<dl>
<dd>

#### 📝 Description

<dl>
<dd>

<dl>
<dd>

Creates a scholarship with its name, description, dates, financial totals, payment schedule,
department and donor associations, custom-field answers, and applicant-notification settings.
Errors come back with a stable `code`, a readable `message`, and per-field
`details`, so a caller can tell what to correct.


This endpoint enforces exactly the same rules as creating a scholarship in the AwardSpring
admin interface, so anything rejected here would also have been rejected there.


A typical call supplies the award's name, dates falling inside the active award cycle, a
budget total, and any donor association. The response returns the new
`scholarship_id`, which you can use in follow-up requests.


Set `dry_run=true` (query param or `Dry-Run: true` header) to validate without persisting.

<b>Idempotency:</b> the request supports an optional `Idempotency-Key` header (any
printable-ASCII string, 1–255 chars — UUIDs work well). When supplied, the same key plus
the same request body within 24 hours returns the original response verbatim without
creating a duplicate scholarship. The same key with a different body returns
`422 idempotency_key_request_mismatch`; a concurrent retry while the first call is
still executing returns `409 idempotency_key_in_flight`. Only 2xx responses are
cached — 4xx/5xx leave the key available for retry with a corrected payload.
</dd>
</dl>
</dd>
</dl>

#### 🔌 Usage

<dl>
<dd>

<dl>
<dd>

```php
$client->scholarships->create(
    new CreateScholarshipV1Request([
        'awardCycleId' => 1,
    ]),
);
```
</dd>
</dl>
</dd>
</dl>

#### ⚙️ Parameters

<dl>
<dd>

<dl>
<dd>

**$scholarshipName:** `?string` — Human-readable name of the scholarship. Required, non-whitespace. Example: `"Spring 2026 STEM Award"`.
    
</dd>
</dl>

<dl>
<dd>

**$fundId:** `?string` 

Optional external fund identifier (matches a `Fund.FundIdName` in the tenant's funds
directory). When Funds Management is enabled and the supplied ID does not match an existing
fund, a new fund row is created automatically. When it matches, the scholarship is linked to
that fund. Example: `"GEN-2026"`.
    
</dd>
</dl>

<dl>
<dd>

**$scholarshipDescription:** `?string` 

Free-form description of the scholarship. Required, non-whitespace. Shown to applicants in
the scholarship details panel. Example: `"Award for incoming STEM majors with financial need."`.
    
</dd>
</dl>

<dl>
<dd>

**$isSpecialFunds:** `?bool` 

Whether this is a Special Funds scholarship (a tenant-controlled cap on how many of these
can exist per award cycle). Defaults to `false`. Cannot be combined with
IsInstitutionalAwardScholarship — a request with both set to `true` is
rejected with a `validation_failed` error.
    
</dd>
</dl>

<dl>
<dd>

**$awardCycleId:** `int` — AwardSpring award-cycle identifier this scholarship belongs to. Required. Example: `12`.
    
</dd>
</dl>

<dl>
<dd>

**$applicationStartDate:** `?string` 

ISO 8601 datetime when applicants can begin applying. Example: `"2026-08-01T00:00:00"`.
Interpreted in the tenant's configured time zone.
    
</dd>
</dl>

<dl>
<dd>

**$applicationEndDate:** `?string` 

ISO 8601 datetime when applications close. The persistence layer normalizes this to the end
of the chosen day. Example: `"2026-10-15T23:59:59"`. Must be after
ApplicationStartDate and within the parent award cycle's date range.
    
</dd>
</dl>

<dl>
<dd>

**$disbursementDate:** `?string` 

ISO 8601 date of the first disbursement to awarded students. Required — the admin UI
requires it on every scholarship, and this endpoint enforces the same rule. Defaults are
not applied server-side; send the institution's intended first payment date. Example:
`"2027-01-15"`.
    
</dd>
</dl>

<dl>
<dd>

**$firstDisbursementTermName:** `?string` 

Optional label for the first disbursement's academic term, shown alongside the
disbursement date. Maximum 50 characters. Example: `"Spring 2027"`.
    
</dd>
</dl>

<dl>
<dd>

**$totalAwardsNumber:** `?int` 

Optional total number of recipients to award. Combined with TotalScholarshipValue
to derive a per-award amount. Must be a positive integer (1 to 999,999) when supplied. Example: `10`.
    
</dd>
</dl>

<dl>
<dd>

**$totalScholarshipValue:** `?float` 

Optional total dollar value of the scholarship across all recipients. Combined with
TotalAwardsNumber to derive a per-award amount. Must be non-negative when
supplied. Example: `50000.00`.
    
</dd>
</dl>

<dl>
<dd>

**$paymentsPerAward:** `?int` 

Optional number of payments each recipient receives. Must be 1 or greater when supplied.
Example: `2`.
    
</dd>
</dl>

<dl>
<dd>

**$departmentId:** `?int` 

Optional AwardSpring department ID the scholarship is owned by. Used for Department Admin
scoping. Example: `3`.
    
</dd>
</dl>

<dl>
<dd>

**$donorIds:** `?array` 

Optional list of donor or donor-organization user IDs associated with the scholarship.
Example: `[101, 102]`.
    
</dd>
</dl>

<dl>
<dd>

**$isDeactivateScholarship:** `?bool` 

Whether the scholarship is deactivated on create (rare — typically left `false`).
Example: `false`.
    
</dd>
</dl>

<dl>
<dd>

**$isInstitutionalAwardScholarship:** `?bool` 

Whether this is an Institutional Award scholarship (recipients selected by the institution
rather than through a public application flow). Cannot be combined with
IsSpecialFunds — a request with both set to `true` is rejected with a
`validation_failed` error. Example: `false`.
    
</dd>
</dl>

<dl>
<dd>

**$internalNotes:** `?string` 

Optional internal notes for admin staff. Not shown to applicants. Example:
`"Cycle 2026 STEM partnership with ACME Corp."`.
    
</dd>
</dl>
</dd>
</dl>


</dd>
</dl>
</details>

<details><summary><code>$client-&gt;scholarships-&gt;listAvailableDollars($request) -> ?ScholarshipAvailableDollarsV1ListResponse</code></summary>
<dl>
<dd>

#### 🔌 Usage

<dl>
<dd>

<dl>
<dd>

```php
$client->scholarships->listAvailableDollars(
    new ListAvailableDollarsScholarshipsRequest([
        'awardCycleId' => 1,
    ]),
);
```
</dd>
</dl>
</dd>
</dl>

#### ⚙️ Parameters

<dl>
<dd>

<dl>
<dd>

**$awardCycleId:** `int` 
    
</dd>
</dl>

<dl>
<dd>

**$limit:** `?int` — Page size. Defaults to 25. Values outside 1–100 are clamped rather than rejected.
    
</dd>
</dl>

<dl>
<dd>

**$startingAfter:** `?string` — Opaque cursor. Pass the `next_cursor` from the previous page to page forward. Mutually exclusive with `ending_before`; if both are supplied, `starting_after` wins.
    
</dd>
</dl>

<dl>
<dd>

**$endingBefore:** `?string` — Opaque cursor. Pass the `previous_cursor` from the current page to page backward.
    
</dd>
</dl>
</dd>
</dl>


</dd>
</dl>
</details>

<details><summary><code>$client-&gt;scholarships-&gt;getAvailableDollars($scholarshipId, $request) -> ?ScholarshipAvailableDollarsV1</code></summary>
<dl>
<dd>

#### 🔌 Usage

<dl>
<dd>

<dl>
<dd>

```php
$client->scholarships->getAvailableDollars(
    1,
    new GetAvailableDollarsScholarshipsRequest([
        'awardCycleId' => 1,
    ]),
);
```
</dd>
</dl>
</dd>
</dl>

#### ⚙️ Parameters

<dl>
<dd>

<dl>
<dd>

**$scholarshipId:** `int` 
    
</dd>
</dl>

<dl>
<dd>

**$awardCycleId:** `int` 
    
</dd>
</dl>
</dd>
</dl>


</dd>
</dl>
</details>

<details><summary><code>$client-&gt;scholarships-&gt;listAwardedStudents($request) -> ?AwardedStudentV1ListResponse</code></summary>
<dl>
<dd>

#### 🔌 Usage

<dl>
<dd>

<dl>
<dd>

```php
$client->scholarships->listAwardedStudents(
    new ListAwardedStudentsScholarshipsRequest([
        'awardCycleId' => 1,
    ]),
);
```
</dd>
</dl>
</dd>
</dl>

#### ⚙️ Parameters

<dl>
<dd>

<dl>
<dd>

**$awardCycleId:** `int` 
    
</dd>
</dl>

<dl>
<dd>

**$scholarshipId:** `?int` 
    
</dd>
</dl>

<dl>
<dd>

**$limit:** `?int` — Page size. Defaults to 25. Values outside 1–100 are clamped rather than rejected.
    
</dd>
</dl>

<dl>
<dd>

**$startingAfter:** `?string` — Opaque cursor. Pass the `next_cursor` from the previous page to page forward. Mutually exclusive with `ending_before`; if both are supplied, `starting_after` wins.
    
</dd>
</dl>

<dl>
<dd>

**$endingBefore:** `?string` — Opaque cursor. Pass the `previous_cursor` from the current page to page backward.
    
</dd>
</dl>
</dd>
</dl>


</dd>
</dl>
</details>

