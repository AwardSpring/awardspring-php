# Reference
## Applications
<details><summary><code>$client-&gt;applications-&gt;list($request) -> ?ApplicationV1ListResponse</code></summary>
<dl>
<dd>

#### 📝 Description

<dl>
<dd>

<dl>
<dd>

Returns one page at a time. Each row names the scholarship and award cycle it belongs to,
and carries a `status` of `applied`, `awarded`, or `denied`. Without
filters the list covers the whole institution; narrow it with `user_id` (a
student's `id` from the Students endpoints) to scope to a single student. Returns
`404 student_not_found`, naming `user_id` in `param`, when the `user_id` is not
a student at this institution.

Assistant capability: `springiq_list_student_applications`. Required permission: Users (applies when called on behalf of a signed-in person).
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
$client->applications->list(
    new ListApplicationsRequest([]),
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

**$userId:** `?string` — Optional student scope (`?user_id=`): the student's `id` from the Students endpoints. Omit for a tenant-wide list.
    
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

<dl>
<dd>

**$include_:** `?string` — Optional extras to add to the response, comma-separated. `total_count` is the only supported value: it adds `total_count`, the number of records that match this request across all pages. The count is taken fresh at the moment of each request, so it can change while you page; ask for it on the first page. Without it, `total_count` is left out of the response. Asking or not never invalidates a cursor. Any other value returns `400 validation_failed`.
    
</dd>
</dl>
</dd>
</dl>


</dd>
</dl>
</details>

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

Assistant capability: `springiq_list_award_cycles`.
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

<dl>
<dd>

**$include_:** `?string` — Optional extras to add to the response, comma-separated. `total_count` is the only supported value: it adds `total_count`, the number of records that match this request across all pages. The count is taken fresh at the moment of each request, so it can change while you page; ask for it on the first page. Without it, `total_count` is left out of the response. Asking or not never invalidates a cursor. Any other value returns `400 validation_failed`.
    
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

Assistant capability: `springiq_get_current_award_cycle`.
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

<details><summary><code>$client-&gt;awardCycles-&gt;getAvailableDollarsSummary($awardCycleId) -> ?AvailableDollarsSummaryV1</code></summary>
<dl>
<dd>

#### 📝 Description

<dl>
<dd>

<dl>
<dd>

Returns one available-dollars summary (`object: "available_dollars_summary"`): the budget, awarded and remaining amounts of
every active scholarship in the award cycle added together, and how many of those scholarships are
over-awarded. The totals add up the same scholarships and amounts that
`GET /api/v1/scholarships/available-dollars` lists for the same cycle, so use this endpoint for a
cycle-wide total instead of paging through that list. An over-awarded scholarship (one that has awarded
more than its budget) is included and counts against `total_amount_remaining`. A cycle with no
active scholarships returns zeros, not an error. An award cycle ID the institution does not have returns
`404 award_cycle_not_found` naming `awardCycleId`; to find the cycle running now, call
`GET /api/v1/award-cycles/current`.

Assistant capability: `springiq_get_available_dollars_summary`. Required permission: Scholarships (applies when called on behalf of a signed-in person).
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
$client->awardCycles->getAvailableDollarsSummary(
    'awardCycleId',
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

**$awardCycleId:** `string` — The award cycle's stored ID.
    
</dd>
</dl>
</dd>
</dl>


</dd>
</dl>
</details>

## Awards
<details><summary><code>$client-&gt;awards-&gt;list($request) -> ?AwardV1ListResponse</code></summary>
<dl>
<dd>

#### 📝 Description

<dl>
<dd>

<dl>
<dd>

Returns one page at a time. Each row names the awarded scholarship and its award cycle,
and carries the awarded amount and decision date. Without filters the list covers the
whole institution; narrow it with `user_id` (a student's `id` from the
Students endpoints) to scope to a single student. Returns `404 student_not_found`,
naming `user_id` in `param`, when the `user_id` is not a student at this institution.

Assistant capability: `springiq_list_student_awards`. Required permission: Users (applies when called on behalf of a signed-in person).
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
$client->awards->list(
    new ListAwardsRequest([]),
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

**$userId:** `?string` — Optional student scope (`?user_id=`): the student's `id` from the Students endpoints. Omit for a tenant-wide list.
    
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

<dl>
<dd>

**$include_:** `?string` — Optional extras to add to the response, comma-separated. `total_count` is the only supported value: it adds `total_count`, the number of records that match this request across all pages. The count is taken fresh at the moment of each request, so it can change while you page; ask for it on the first page. Without it, `total_count` is left out of the response. Asking or not never invalidates a cursor. Any other value returns `400 validation_failed`.
    
</dd>
</dl>
</dd>
</dl>


</dd>
</dl>
</details>

## Departments
<details><summary><code>$client-&gt;departments-&gt;list($request) -> ?DepartmentV1ListResponse</code></summary>
<dl>
<dd>

#### 📝 Description

<dl>
<dd>

<dl>
<dd>

Returns the departments of the institution your API key is issued for, in the standard list envelope
(`object: "list"`, `has_more`, `next_cursor`, `previous_cursor`, `data`),
each with how many differently named scholarships and how many administrators belong to it. Page
forward by passing the returned `next_cursor` as `starting_after`. `limit` defaults to
25 and is capped at 100. Results are ordered by id ascending. Filter with `?q=`
(case-insensitive substring match on the department name).
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
$client->departments->list(
    new ListDepartmentsRequest([]),
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

**$q:** `?string` — Case-insensitive substring match on the department name (`?q=`).
    
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

<dl>
<dd>

**$include_:** `?string` — Optional extras to add to the response, comma-separated. `total_count` is the only supported value: it adds `total_count`, the number of records that match this request across all pages. The count is taken fresh at the moment of each request, so it can change while you page; ask for it on the first page. Without it, `total_count` is left out of the response. Asking or not never invalidates a cursor. Any other value returns `400 validation_failed`.
    
</dd>
</dl>
</dd>
</dl>


</dd>
</dl>
</details>

<details><summary><code>$client-&gt;departments-&gt;create($request) -> ?DepartmentV1</code></summary>
<dl>
<dd>

#### 📝 Description

<dl>
<dd>

<dl>
<dd>

Adds one department, with the same rules as adding a department in the AwardSpring admin interface:
the name is required and must not already be used by another department at the institution, ignoring
upper and lower case and spaces at either end. A new department starts with the same settings the
admin interface gives one. Departments are not available on every plan; on a plan without them this
returns `403 permission_required`.

<b>Idempotency:</b> the request supports an optional `Idempotency-Key` header (any
printable-ASCII string, 1–255 chars — UUIDs work well). When supplied, the same key plus the same
request body within 24 hours returns the original response verbatim without creating a duplicate
department. The same key with a different body returns `422 idempotency_key_request_mismatch`; a
concurrent retry while the first call is still executing returns `409 idempotency_key_in_flight`.
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
$client->departments->create(
    new CreateDepartmentV1Request([]),
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

**$name:** `?string` 

The department's name. Required, and not already used by another department at the institution,
ignoring upper and lower case and spaces at either end. Example: `"School of Nursing"`.
    
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

#### 📝 Description

<dl>
<dd>

<dl>
<dd>

Assistant capability: `springiq_list_donor_activities`. Required permission: Donors (applies when called on behalf of a signed-in person).
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
$client->donorActivities->list(
    'donorId',
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

**$donorId:** `string` 
    
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

<dl>
<dd>

**$include_:** `?string` — Optional extras to add to the response, comma-separated. `total_count` is the only supported value: it adds `total_count`, the number of records that match this request across all pages. The count is taken fresh at the moment of each request, so it can change while you page; ask for it on the first page. Without it, `total_count` is left out of the response. Asking or not never invalidates a cursor. Any other value returns `400 validation_failed`.
    
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


Assistant capability: `springiq_create_activity`. Required permission: Donors (applies when called on behalf of a signed-in person).
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
    'donorId',
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

**$donorId:** `string` — The stored ID of the donor (or donor organization) to attach the activity to.
    
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

**$campaignId:** `?string` 

Optional stored ID of a campaign at this institution, for pledge activities. Only meaningful when
ActivityType is `LoggedPledge`; ignored for non-monetary activity types.
Example: `"7HQ2KW9XR"`.
    
</dd>
</dl>

<dl>
<dd>

**$assignedToUserId:** `?string` 

Optional stored ID of the staff member the activity is assigned to (e.g., the donor manager who should
follow up). Pass `null` or omit when no assignment is intended. Example: `"4MZ8RT2PW"`.
    
</dd>
</dl>

<dl>
<dd>

**$isCompleted:** `?bool` 

Whether the activity is completed. For non-monetary activity types this is ignored (treated as `false`).
For `LoggedPledge`, `true` indicates the pledge has been fulfilled. Defaults to `false`.
    
</dd>
</dl>

<dl>
<dd>

**$place:** `?string` 

Optional place for the meeting or call: a room, an address or a call link, up to 500 characters after
surrounding spaces are removed. Empty or only spaces means no place. Example: `"Alumni House, Room 204"`.
    
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

#### 📝 Description

<dl>
<dd>

<dl>
<dd>

Assistant capability: `springiq_get_donor_activity`. Required permission: Donors (applies when called on behalf of a signed-in person).
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
$client->donorActivities->get(
    'donorId',
    'activityId',
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

**$donorId:** `string` 
    
</dd>
</dl>

<dl>
<dd>

**$activityId:** `string` 
    
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

Returns one page at a time. With no `sort` and no `q`, donors are listed by
`id`, lowest first.


Sort with `sort`. `name` lists donors A to Z, ignoring case: an organization by its
organization name, and a person by last name, then first name. A person with no last name
is listed by first name, and a donor with no name at all comes last. `last_contacted`
lists the most recently contacted donors first, by `last_contacted_at`; donors who
have never been contacted come last. Donors who tie are listed by `id`: lowest first
for `name`, highest first for `last_contacted`.


Narrow the list with `level` to donors at one or more levels, for example
`level=major,principal`. A donor's level comes from their giving, so a request made as
a signed-in user who may not see donors' giving returns `403 permission_denied` when
it filters by level. Sorting is open to everyone who can list donors.


Narrow the list with `giving_status` to donors with one or more giving statuses, for
example `giving_status=recurring,new`. `Recurring`: the donor has a recurring gift
that is still running (not cancelled or stopped, and not past its end date) and at least one
payment from it has gone through. `New`: the donor's first gift was in the last 12
months. `Lapsed`: the donor's last gift was more than 12 months ago. The 12 months count
back from now, not from the start of a fiscal year. A donor who fits more than one takes the
first of `Recurring`, `New`, `Lapsed`. A donor who has never given has no giving
status, and neither does one whose first gift was more than 12 months ago and who has given
again within the last 12 months. With `level` as well, a donor must match both. A
request made as a signed-in user who may not see donors' giving returns
`403 permission_denied` when it filters by giving status.


Search with `q` (at least `2` characters). Every word you type must match the
start of a word in the donor's first name, last name or organization, or the start of
their email address, so `jane smi` finds Jane Smith. Matches inside a word are not
found: `co` finds `Coworking` but not `Waco`. Where digits and letters
touch, a word is also split there, so `25co` finds `25N Coworking`.


Without `sort`, search results come in three groups: first, donors whose full name or
organization starts with the search (for a search with no spaces, compared with the spaces
taken out, so `janesmith` finds Jane Smith); next, donors where every word starts a
word in their name or organization; last, donors matched only through their email address.
Within a group, donors are listed by `id`, lowest first, so the order holds from one
page to the next. With `sort`, the search only decides which donors are listed and
the sort decides their order.

`sort`, `level`, `giving_status` and `q` work together. Send the same ones
on every page: a cursor sent with a different `sort`, `level`, `giving_status`
or `q` returns `400 invalid_cursor`. A search shorter than `2` characters, or an
unknown `level`, `giving_status` or `sort`, returns `400 validation_failed`
with `param` set to the parameter at fault.


Assistant capability: `springiq_list_donors`. Required permission: Donors (applies when called on behalf of a signed-in person).
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

Search text (`?q=`), at least `2` characters; a shorter search returns
`400 validation_failed`. Each word must match the start of a word in the donor's first
name, last name or organization, or the start of their email address, ignoring case:
`co` finds `Coworking` and `cole@school.edu` but not `Waco`. Where digits
and letters touch, a word is also split there, so `25co` searches as `25 co`. A
search with no spaces also matches the start of the full name or organization with its
spaces taken out, so `janesmith` finds Jane Smith. Leave it out to list every donor.
    
</dd>
</dl>

<dl>
<dd>

**$level:** `?string` 

Only donors at these levels (`?level=`): `Annual`, `Mid`, `Major` or
`Principal`, the values of the donor's `level` field, in any case. Send several
comma-separated (`level=major,principal`) or repeat the parameter; a donor at any of
them is listed. Any other value returns `400 validation_failed`. Level is worked out
from giving, so a signed-in user who may not see donors' giving gets
`403 permission_denied`. Leave it out for every level.
    
</dd>
</dl>

<dl>
<dd>

**$givingStatus:** `?string` 

Only donors with these giving statuses (`?giving_status=`): `Recurring`, `New`
or `Lapsed`, the values of the donor's `giving_status` field, in any case. Send
several comma-separated (`giving_status=recurring,new`) or repeat the parameter; a donor
with any of them is listed. With `level` as well, a donor must match both. Any other
value returns `400 validation_failed`. Giving status is worked out from giving, so a
signed-in user who may not see donors' giving gets `403 permission_denied`. Leave it
out for every donor, including those with no giving status.
    
</dd>
</dl>

<dl>
<dd>

**$sort:** `?string` 

The order to list donors in (`?sort=`): `name` or `last_contacted`. Leave it
out for the default order. Any other value returns `400 validation_failed`.
    
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

<dl>
<dd>

**$include_:** `?string` — Optional extras to add to the response, comma-separated. `total_count` is the only supported value: it adds `total_count`, the number of records that match this request across all pages. The count is taken fresh at the moment of each request, so it can change while you page; ask for it on the first page. Without it, `total_count` is left out of the response. Asking or not never invalidates a cursor. Any other value returns `400 validation_failed`.
    
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

Assistant capability: `springiq_create_donor`. Required permission: Donors (applies when called on behalf of a signed-in person).
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
gift, their notes, the tags on the donor, and their answers to the institution's donor custom
fields. `id` is the donor's stored ID. Returns `404 donor_not_found`
naming `id` if no such donor belongs to this institution.

Assistant capability: `springiq_get_donor_profile`. Required permission: Donors (applies when called on behalf of a signed-in person).
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
    'id',
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

**$id:** `string` 
    
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

<b>Administrator accounts:</b> a program with an API key never changes an account that holds an
administrator role. A donor who is also an administrator returns `400 validation_failed` naming
`id`, and nothing is saved; change them in AwardSpring instead.
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
    'id',
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

**$id:** `string` 
    
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

Replaces the donor's free-text notes. `id` is the donor's stored ID. Returns
`404 donor_not_found` naming `id` if no such donor belongs to this institution.

<b>Administrator accounts:</b> a program with an API key never changes an account that holds an
administrator role. A donor who is also an administrator returns `400 validation_failed` naming
`id`, and the notes are not saved; change them in AwardSpring instead.
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
    'id',
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

**$id:** `string` 
    
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

Assistant capability: `springiq_list_funds`. Required permission: Funds (applies when called on behalf of a signed-in person).
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

<dl>
<dd>

**$include_:** `?string` — Optional extras to add to the response, comma-separated. `total_count` is the only supported value: it adds `total_count`, the number of records that match this request across all pages. The count is taken fresh at the moment of each request, so it can change while you page; ask for it on the first page. Without it, `total_count` is left out of the response. Asking or not never invalidates a cursor. Any other value returns `400 validation_failed`.
    
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

Returns one page at a time. Without filters the list covers the whole
institution. Narrow it with `donor_id` (a donor's stored ID) to scope to a single donor, `type` to
return only gifts or only pledges, and `q` to search the subject and description.


With no `sort`, gifts are listed in the order they were recorded, earliest recorded first.
Sort by gift `date` with `sort`: `date_desc` lists the newest gift date first and
`date_asc` the oldest first. Gifts that share a date keep the same order from one page
to the next: the most recently recorded first for `date_desc`, the earliest recorded first
for `date_asc`.

`sort`, `donor_id`, `type` and `q` work together. Send the same ones on every
page: a cursor sent with a different `sort`, `donor_id`, `type` or `q` returns
`400 invalid_cursor`. An unknown `sort` returns `400 validation_failed` with
`param` set to `sort`.


Assistant capability: `springiq_list_gifts`. Required permission: Donors (applies when called on behalf of a signed-in person).
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

**$donorId:** `?string` — Optional donor scope (`?donor_id=`): the donor's stored ID. Omit for a tenant-wide list.
    
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

**$sort:** `?string` 

The order to list gifts in (`?sort=`): `date_desc` for the newest gift date first, or
`date_asc` for the oldest gift date first. Leave it out for the order the gifts were
recorded in. Any other value returns `400 validation_failed`.
    
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

<dl>
<dd>

**$include_:** `?string` — Optional extras to add to the response, comma-separated. `total_count` is the only supported value: it adds `total_count`, the number of records that match this request across all pages. The count is taken fresh at the moment of each request, so it can change while you page; ask for it on the first page. Without it, `total_count` is left out of the response. Asking or not never invalidates a cursor. Any other value returns `400 validation_failed`.
    
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

**$donorId:** `?string` — The stored ID of the donor (or donor organization) the gift is attributed to. Required.
    
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

**$campaignId:** `?string` — The stored ID of a campaign at this institution to attribute the gift to. Optional.
    
</dd>
</dl>

<dl>
<dd>

**$isCompleted:** `?bool` — Only meaningful for pledges — marks the pledge fulfilled. Ignored for gifts.
    
</dd>
</dl>

<dl>
<dd>

**$assignedToUserId:** `?string` 

The stored ID of the officer the record is assigned to. Must be a person at this institution when
supplied.
    
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

<details><summary><code>$client-&gt;gifts-&gt;get($id) -> ?GiftV1</code></summary>
<dl>
<dd>

#### 📝 Description

<dl>
<dd>

<dl>
<dd>

Fetches one gift or pledge by its `id` from the gifts list, with the same fields and values
the list returns for it. Returns `404 gift_not_found`, naming `id` in `param`, when
no gift or pledge with that ID belongs to this institution, including when the ID belongs to
another kind of logged activity such as an email or a meeting.
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
$client->gifts->get(
    'id',
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

**$id:** `string` — The gift's or pledge's stored ID, as the gifts list returns it in `id`.
    
</dd>
</dl>
</dd>
</dl>


</dd>
</dl>
</details>

<details><summary><code>$client-&gt;gifts-&gt;update($id, $request) -> ?GiftV1</code></summary>
<dl>
<dd>

#### 📝 Description

<dl>
<dd>

<dl>
<dd>

Changes a gift's or pledge's amount, gift date, payment type, fund, campaign, subject and description,
with the same rules the gift's edit window applies. Send only the fields to change: a field left out
keeps its value. Send an empty string to clear `fund_id`, `campaign_id` or
`description`; the other fields cannot be cleared.


The donor, the record type (`type`), soft credits, `is_completed`,
`assigned_to_user_id`, `gift_acknowledgement_sent` and any field not listed in the request
body cannot be changed here. A request that sends one, or a value that is refused, returns
`400 validation_failed`, and nothing in the request is saved. Each refused field is named in
`details`, and in `param` when only one field is refused; a refusal from the gift's own save rules
(for example a zero amount, or a closed fund) names no field.
On a gift given online, the amount, gift date and payment type cannot be changed; its other fields can.
A pledge has no payment type, and `Online` cannot be chosen as a payment type.


Returns the gift or pledge as `GET /api/v1/gifts/{id}` returns it after the change. Returns
`404 gift_not_found`, naming `id` in `param`, when no gift or pledge with that ID belongs
to this institution. Send `dry_run` to check the change and see the result without saving it,
and an `Idempotency-Key` header so a retry returns the first answer.
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
$client->gifts->update(
    'id',
    new UpdateGiftV1Request([]),
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

**$id:** `string` — The gift's or pledge's stored ID, as the gifts list returns it in `id`.
    
</dd>
</dl>

<dl>
<dd>

**$amount:** `?float` 

The new amount. Must be greater than zero; zero is allowed only on a gift paid `InKind`. Cannot be
cleared. Refused on a gift given online.
    
</dd>
</dl>

<dl>
<dd>

**$giftDate:** `?string` 

The new date of the gift or pledge, as an ISO 8601 date such as `"2026-04-03"`. The date is the
institution's own calendar day; a time sent with it is ignored. Cannot be cleared. Refused on a gift
given online.
    
</dd>
</dl>

<dl>
<dd>

**$giftType:** `?string` 

The new payment type of a gift: one of `Cash`, `Check`, `CreditOrDebit`,
`BankTransfer`, `StockOrProperty`, `InKind`, `PayrollDeduction` or `Other`.
`Online` is set only on gifts given online and cannot be chosen. Cannot be cleared. Refused on a
pledge, which has no payment type, and on a gift given online.
    
</dd>
</dl>

<dl>
<dd>

**$fundId:** `?string` 

The institution's own code for the fund the gift is directed toward, as the gift returns it in
`fund_id`. When the institution uses Funds Management it must name one of its funds. Send an
empty string to clear it.
    
</dd>
</dl>

<dl>
<dd>

**$campaignId:** `?string` — The stored ID of a campaign at this institution. Send an empty string to clear it.
    
</dd>
</dl>

<dl>
<dd>

**$subject:** `?string` — The new short title of the gift or pledge. Cannot be cleared.
    
</dd>
</dl>

<dl>
<dd>

**$description:** `?string` — The new notes about the gift or pledge. Replaces the notes whole. Send an empty string to clear them.
    
</dd>
</dl>
</dd>
</dl>


</dd>
</dl>
</details>

## Payment Schedules
<details><summary><code>$client-&gt;paymentSchedules-&gt;list($request) -> ?PaymentScheduleV1ListResponse</code></summary>
<dl>
<dd>

#### 📝 Description

<dl>
<dd>

<dl>
<dd>

Returns the payment schedules belonging to the institution your API key is issued for, in the list
envelope (`object: "list"`, `has_more`, `next_cursor`, `previous_cursor`,
`data`). Page forward by passing the returned `next_cursor` as `starting_after`;
the last page has `next_cursor: null`. `limit` defaults to 25 and is capped at 100.
Each schedule's `id` is its stored ID (9 characters, capital letters and numbers): pass it
exactly as returned in a scholarship's `payment_schedule_ids`. Results come in the order the
schedules were created; sort by `payment_date` to get the order `payment_schedule_ids`
expects. Filter with `?award_cycle_id=` to list only the schedules configured on that award
cycle; an award cycle the institution does not have returns `404 award_cycle_not_found`.

Assistant capability: `springiq_list_payment_schedules`.
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
$client->paymentSchedules->list(
    new ListPaymentSchedulesRequest([]),
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

**$awardCycleId:** `?string` 

Only the schedules configured on this award cycle (`?award_cycle_id=`), by its stored ID. An
award cycle the institution does not have returns `404 award_cycle_not_found`. Example:
`"4MZ8QW2TC"`.
    
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

<dl>
<dd>

**$include_:** `?string` — Optional extras to add to the response, comma-separated. `total_count` is the only supported value: it adds `total_count`, the number of records that match this request across all pages. The count is taken fresh at the moment of each request, so it can change while you page; ask for it on the first page. Without it, `total_count` is left out of the response. Asking or not never invalidates a cursor. Any other value returns `400 validation_failed`.
    
</dd>
</dl>
</dd>
</dl>


</dd>
</dl>
</details>

## People
<details><summary><code>$client-&gt;people-&gt;archive($id) -> ?PersonV1</code></summary>
<dl>
<dd>

#### 📝 Description

<dl>
<dd>

<dl>
<dd>

Archives the account of a student or reviewer at the institution your API key is issued for. The person
cannot sign in until the account is restored; their applications, reviews and everything else are kept.
An API key can archive students and reviewers only: a person who holds any other role, such as an
administrator or a donor, returns `400 validation_failed`, and is archived in AwardSpring instead.
Archiving a person who is already archived changes nothing and returns the person. The archive is
recorded under your API key. An `id` that names no person at the institution returns
`404 person_not_found`, naming `id` in `param`.
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
$client->people->archive(
    'id',
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

**$id:** `string` — The person's id, as `GET /api/v1/people` returns it.
    
</dd>
</dl>
</dd>
</dl>


</dd>
</dl>
</details>

<details><summary><code>$client-&gt;people-&gt;restore($id) -> ?PersonV1</code></summary>
<dl>
<dd>

#### 📝 Description

<dl>
<dd>

<dl>
<dd>

Restores the archived account of a student or reviewer at the institution your API key is issued for, so
the person can sign in again. An API key can restore students and reviewers only: a person who holds any
other role returns `400 validation_failed`, and is restored in AwardSpring instead. Restoring a person
who is not archived changes nothing and returns the person. An `id` that names no person at the
institution returns `404 person_not_found`, naming `id` in `param`.
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
$client->people->restore(
    'id',
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

**$id:** `string` — The person's id, as `GET /api/v1/people` returns it.
    
</dd>
</dl>
</dd>
</dl>


</dd>
</dl>
</details>

<details><summary><code>$client-&gt;people-&gt;list($request) -> ?PersonV1ListResponse</code></summary>
<dl>
<dd>

#### 📝 Description

<dl>
<dd>

<dl>
<dd>

Returns the people the institution's Users list shows, for the institution your API key is issued
for, in the standard list envelope (`object: "list"`, `has_more`, `next_cursor`,
`previous_cursor`, `data`). Each person carries their roles, the departments an
administrator is limited to, whether the account is archived, and an administrator's permissions.
Page forward by passing the returned `next_cursor` as `starting_after`. `limit`
defaults to 25 and is capped at 100. Results are ordered by id ascending.


Filter with `?role=` (`administrators`, `reviewers`, `students` or
`donors`), `?q=` (part of a name, the start of an email address or student number, or an
exact id) and `?status=` (`all`, `active` or `archived`; archived accounts are
listed by default, as on the Users list).
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
$client->people->list(
    new ListPeopleRequest([]),
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

**$role:** `?string` 

Which tab of the Users list (`?role=`): `administrators`, `reviewers`, `students`
or `donors`. The reviewers tab also lists anyone on a review group in the current award cycle.
    
</dd>
</dl>

<dl>
<dd>

**$q:** `?string` 

Free-text search (`?q=`): matches part of a first or last name or organization, the start of
an email address or student number, or a person's exact `id`.
    
</dd>
</dl>

<dl>
<dd>

**$status:** `?string` 

Account status (`?status=`): `all` (the default, as the Users list shows), `active`
or `archived`.
    
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

<dl>
<dd>

**$include_:** `?string` — Optional extras to add to the response, comma-separated. `total_count` is the only supported value: it adds `total_count`, the number of records that match this request across all pages. The count is taken fresh at the moment of each request, so it can change while you page; ask for it on the first page. Without it, `total_count` is left out of the response. Asking or not never invalidates a cursor. Any other value returns `400 validation_failed`.
    
</dd>
</dl>
</dd>
</dl>


</dd>
</dl>
</details>

## Review Groups
<details><summary><code>$client-&gt;reviewGroups-&gt;list($request) -> ?ReviewGroupV1ListResponse</code></summary>
<dl>
<dd>

#### 📝 Description

<dl>
<dd>

<dl>
<dd>

Returns the review groups of one award cycle, in the standard list envelope. `award_cycle_id` is
required and is the award cycle's stored ID. Each group shows its stored ID, its name and its award cycle.
An award cycle the institution does not have returns `404 award_cycle_not_found`.
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
$client->reviewGroups->list(
    new ListReviewGroupsRequest([
        'awardCycleId' => 'award_cycle_id',
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

**$awardCycleId:** `string` 
    
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

<dl>
<dd>

**$include_:** `?string` — Optional extras to add to the response, comma-separated. `total_count` is the only supported value: it adds `total_count`, the number of records that match this request across all pages. The count is taken fresh at the moment of each request, so it can change while you page; ask for it on the first page. Without it, `total_count` is left out of the response. Asking or not never invalidates a cursor. Any other value returns `400 validation_failed`.
    
</dd>
</dl>
</dd>
</dl>


</dd>
</dl>
</details>

## Reviewer Assignments
<details><summary><code>$client-&gt;reviewerAssignments-&gt;list($request) -> ?ReviewerAssignmentV1ListResponse</code></summary>
<dl>
<dd>

#### 📝 Description

<dl>
<dd>

<dl>
<dd>

Returns one row per pair, as the Reviewer Assignment tab shows them, in the standard list envelope. Each row
has the group's name and award cycle, the panel number on a group with two panels, and the reviewer and the
applicant with name, email and stored ID. Rows are ordered by panel, then reviewer, then applicant.


Name the group by `review_group_id`, its stored ID, or by `review_group_name`, optionally with
`award_cycle_id`; leaving both out returns `400 validation_failed`. A group that does not exist at
your institution, including one from another institution, returns `404 review_group_not_found`, and so
does a name that fits no group. A name that fits more than one group returns `400 validation_failed`
naming each. Leave `panel` out to list every panel; send `panel` to list one. A panel the group
does not have returns `404 review_group_panel_not_found`. Send the same filters on every page: they are
recorded in the cursor, and a page request whose filters do not match the cursor's is refused.


Assistant capability: `springiq_list_reviewer_assignments`.
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
$client->reviewerAssignments->list(
    new ListReviewerAssignmentsRequest([]),
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

**$reviewGroupId:** `?string` 

The review group's stored ID (`?review_group_id=`). Send it or `review_group_name`; leaving
both out returns `400 validation_failed`. A group that does not exist at your institution returns
`404 review_group_not_found`.
    
</dd>
</dl>

<dl>
<dd>

**$reviewGroupName:** `?string` 

Optional, instead of `review_group_id`: the review group's name (`?review_group_name=`), matched
whole and ignoring case, or else as part of a name. A name that fits no group returns
`404 review_group_not_found`; one that fits more than one returns `400 validation_failed`
naming each, with its award cycle and stored ID.
    
</dd>
</dl>

<dl>
<dd>

**$awardCycleId:** `?string` 

Optional, with `review_group_name` only: the stored ID of the award cycle to look for the name in
(`?award_cycle_id=`).
    
</dd>
</dl>

<dl>
<dd>

**$panel:** `?int` 

Optional. Only this panel's pairs (`?panel=`): `1` or `2`. Leave it out on a group with one
panel, and to list both panels of a group with two. A panel the group does not have returns
`404 review_group_panel_not_found`.
    
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

<dl>
<dd>

**$include_:** `?string` — Optional extras to add to the response, comma-separated. `total_count` is the only supported value: it adds `total_count`, the number of records that match this request across all pages. The count is taken fresh at the moment of each request, so it can change while you page; ask for it on the first page. Without it, `total_count` is left out of the response. Asking or not never invalidates a cursor. Any other value returns `400 validation_failed`.
    
</dd>
</dl>
</dd>
</dl>


</dd>
</dl>
</details>

<details><summary><code>$client-&gt;reviewerAssignments-&gt;change($request) -> ?ChangeReviewerAssignmentsV1Result</code></summary>
<dl>
<dd>

#### 📝 Description

<dl>
<dd>

<dl>
<dd>

Changes the pairs on one panel of a review group, with the same rules as the Reviewer Assignment tab.
`review_group_id` in the body is required and is the review group's stored ID. Name each person by
email or stored ID. Set `mode` to `add`, `remove` or `replace`; `replace` makes the
panel hold exactly the pairs you send, removing the others.

<b>Checks on every row.</b> The reviewer must already be a reviewer in the group. The applicant must have
applied to a scholarship in the group, and on panel 2 must have been moved up to it. A reviewer is never
given their own application. A pair listed twice, or a person who matches no active account or more than
one, is turned down. A `replace` that would remove a pair whose review is started or finished turns
that pair down unless `allow_removing_started_reviews` is `true`; removing a pair also removes
that reviewer's scores for it, as Unassign does on the tab. Only a pair the tab shows is already in place
or there to remove: adding a pair the tab does not show is turned down, and removing one leaves it as it
is and counts it as `unchanged`.

<b>All or nothing.</b> If any row is turned down, nothing changes and the answer is
`400 assignment_rows_rejected`, whose `details` list every row with its reason. Rows that pass
show `not_applied`. With `apply_good_rows_only` set to `true`, the rows that pass are
applied and each turned-down row is listed in the `200`. A `replace` is the exception: one row
whose reviewer or applicant matches no active account, or more than one, refuses the whole request with
`400 assignment_rows_rejected` even with `apply_good_rows_only`, because that row could name a
pair the panel holds and a `replace` removes every pair it does not list.

<b>Large requests.</b> A request with more than 500 rows, or more than 500 pairs to change, is accepted at
once with `202` and a `reviewer_assignment_job`, which names the review group and its award
cycle as every answer does. Read `status_url` to follow it. Under
all-or-nothing, a pair that becomes invalid while the job runs stops the job: batches already written
stay written, and the job says so. At most 20,000 rows are accepted in one request, and a request may
cover at most 20,000 pairs counting the pairs a `replace` removes because the request leaves them out;
a larger one is refused with `400 validation_failed`.

<b>The panel's assignment choice is never changed.</b> Every answer shows it as `assignment_choice`.
When it is `all` or `random`, the answer carries a `warning`: applying that choice again in
AwardSpring would replace the pairs you loaded.


Each pair added is recorded as added by your API key. Send `dry_run` to check the request without
changing anything, and an `Idempotency-Key` header so a retry returns the original answer; for a
background job, the same job. Sending the same request again also ends in the same pairs.
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
$client->reviewerAssignments->change(
    new ChangeReviewerAssignmentsV1Request([]),
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

**$reviewGroupId:** `?string` 

Required. The review group's stored ID. Leaving it out returns `400 validation_failed`; a group that
does not exist at your institution returns `404 review_group_not_found`.
    
</dd>
</dl>

<dl>
<dd>

**$mode:** `?string` 

Required. `add` adds the pairs, `remove` removes them, and `replace` makes the panel hold
exactly these pairs: pairs not listed are removed and new ones are added. Any other value returns
`400 validation_failed`.
    
</dd>
</dl>

<dl>
<dd>

**$panel:** `?int` 

Optional. The panel to change, `1` or `2`. Leave it out on a review group with one panel. On a
group with two panels, leaving it out means panel 1. A panel the group does not have returns
`404 review_group_panel_not_found`.
    
</dd>
</dl>

<dl>
<dd>

**$rows:** `?array` — Required. At least one pair and at most 20,000.
    
</dd>
</dl>

<dl>
<dd>

**$applyGoodRowsOnly:** `?bool` 

Optional, default `false`. When `true`, the pairs that pass are applied and each turned-down row
is listed. When `false`, a single turned-down row changes nothing.
    
</dd>
</dl>

<dl>
<dd>

**$allowRemovingStartedReviews:** `?bool` 

Optional, default `false`. When `false`, a `replace` that would remove a pair whose review
is started or finished turns that pair down. When `true` it removes the pair and its scores.
`remove` mode always removes the pairs it lists, as Unassign does on the tab.
    
</dd>
</dl>
</dd>
</dl>


</dd>
</dl>
</details>

<details><summary><code>$client-&gt;reviewerAssignments-&gt;getJob($jobId) -> ?ReviewerAssignmentJobV1</code></summary>
<dl>
<dd>

#### 📝 Description

<dl>
<dd>

<dl>
<dd>

Shows how a background request is going: `status`, how many of its rows are done, and the counts so
far. Like every answer about pairs, it names the review group and its award cycle by stored ID and name.
The job carries totals only; read `rows_url` for each row's result. `completed` means every
row was applied. A job that stopped early, or has any turned-down or failed row, including rows turned down
when the request was accepted, is `partially_completed` and says why in `notices`. A job that
applied nothing because it could not run, or because every row it reached was turned down or failed, is
`failed`. A job code that names no job at your institution, including another institution's, returns
`404 assignment_job_not_found`.
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
$client->reviewerAssignments->getJob(
    'job_id',
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

**$jobId:** `string` 
    
</dd>
</dl>
</dd>
</dl>


</dd>
</dl>
</details>

<details><summary><code>$client-&gt;reviewerAssignments-&gt;listJobRows($request) -> ?ReviewerAssignmentJobRowV1ListResponse</code></summary>
<dl>
<dd>

#### 📝 Description

<dl>
<dd>

<dl>
<dd>

Returns a background request's rows in request order, in the standard list envelope, each with what has
happened to it so far: `waiting` until the job reaches it, then `applied`, `rejected` or
`failed` with a `message` saying why. A row an all-or-nothing job never reached, because a pair
failed its final check, stays `waiting`. When a job stops for any other reason, such as the API key
losing its write access or the job being unable to finish, a row it did not change is `rejected`
with a `message` saying why, and a row whose pair already stands as asked is `applied`. Pairs a
`replace` removes because the request leaves them out come after the
request's own rows and have no `index`.

`job_id` is required and is the job's code from its `status_url`. A code that names no job at your
institution, including another institution's, returns `404 assignment_job_not_found`.
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
$client->reviewerAssignments->listJobRows(
    new ListJobRowsReviewerAssignmentsRequest([]),
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

**$jobId:** `?string` 

Required. The job's code (`?job_id=`), as in its `status_url`. A code that names no job at your
institution returns `404 assignment_job_not_found`.
    
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

<dl>
<dd>

**$include_:** `?string` — Optional extras to add to the response, comma-separated. `total_count` is the only supported value: it adds `total_count`, the number of records that match this request across all pages. The count is taken fresh at the moment of each request, so it can change while you page; ask for it on the first page. Without it, `total_count` is left out of the response. Asking or not never invalidates a cursor. Any other value returns `400 validation_failed`.
    
</dd>
</dl>
</dd>
</dl>


</dd>
</dl>
</details>

## Reviewers
<details><summary><code>$client-&gt;reviewers-&gt;create($request) -> ?ReviewerV1</code></summary>
<dl>
<dd>

#### 📝 Description

<dl>
<dd>

<dl>
<dd>

Adds one reviewer account, with the same rules as adding a reviewer from the Users list in the AwardSpring
admin interface: a first name, a last name and a well-formed email are required, each at most 50
characters. As in the admin interface, a new reviewer gets no welcome email.

<b>An email that already has an account:</b> by default the email must not already be used by any account
at the institution, ignoring upper and lower case; if it is, this returns `400 email_already_exists`.
With `if_email_in_use` set to `add_role`, this does what the admin interface does instead, and no
second account is made. When an active account at the institution has the email, the reviewer role is added
to that account, which keeps its name, email and password, and this returns `200` with that account as
the reviewer. An account that already has the reviewer role is returned unchanged, also with `200`. The
role is never added to an account that holds an administrator role, and this returns
`400 email_already_exists` instead, as it does for an email whose account is deactivated, merged, or one
the admin interface would not change. When only an archived account has the email, that account gets no
role: a new reviewer is made with `201` and the email moves to them, as in the admin interface.
The `AwardSpring-Existing-Account` header says which of these happened: `role_added`,
`role_already_held` or `email_moved_from_archived`. It is absent for an ordinary create, and from
a response replayed for an `Idempotency-Key`.

<b>Idempotency:</b> the request supports an optional `Idempotency-Key` header (any
printable-ASCII string, 1–255 chars — UUIDs work well). When supplied, the same key plus the same
request body within 24 hours returns the original response verbatim without creating a duplicate
reviewer. The same key with a different body returns `422 idempotency_key_request_mismatch`; a
concurrent retry while the first call is still executing returns `409 idempotency_key_in_flight`.
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
$client->reviewers->create(
    new CreateReviewerV1Request([]),
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

**$firstName:** `?string` — The reviewer's first name. Required, at most 50 characters. Example: `"Pat"`.
    
</dd>
</dl>

<dl>
<dd>

**$lastName:** `?string` — The reviewer's last name. Required, at most 50 characters. Example: `"Lee"`.
    
</dd>
</dl>

<dl>
<dd>

**$email:** `?string` 

The reviewer's email, which is also how they sign in. Required, at most 50 characters, and not already
used by any account at the institution, ignoring upper and lower case, unless `if_email_in_use` is
`add_role`. Example: `"pat.lee@school.edu"`.
    
</dd>
</dl>

<dl>
<dd>

**$ifEmailInUse:** `?string` 

Optional. What to do when an account at the institution already has the email. `error`, the default,
returns `400 email_already_exists`. `add_role` does what the Users list's Add user window does:
the reviewer role is added to that account instead of making a second one. Any other value returns
`400 validation_failed`.
    
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

Assistant capability: `springiq_list_scholarships`. Required permission: Scholarships (applies when called on behalf of a signed-in person).
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

<dl>
<dd>

**$include_:** `?string` — Optional extras to add to the response, comma-separated. `total_count` is the only supported value: it adds `total_count`, the number of records that match this request across all pages. The count is taken fresh at the moment of each request, so it can change while you page; ask for it on the first page. Without it, `total_count` is left out of the response. Asking or not never invalidates a cursor. Any other value returns `400 validation_failed`.
    
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


Assistant capability: `springiq_create_scholarship`. Required permission: Scholarships (applies when called on behalf of a signed-in person).
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
    new CreateScholarshipV1Request([]),
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

**$awardCycleId:** `?string` — The stored ID of the award cycle this scholarship belongs to. Required. Example: `"4MZ8QW2TC"`.
    
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
When it is 2 or more, `payment_schedule_ids` must name one payment schedule for each payment
after the first. Example: `2`.
    
</dd>
</dl>

<dl>
<dd>

**$paymentScheduleIds:** `?array` 

The award cycle's payment schedules used for the second and later payments, one per payment after
the first, in date order, named by their stored IDs (9 characters, capital letters and numbers).
Required when `payments_per_award` is 2 or more, and must be left out otherwise. List them with
`GET /api/v1/payment-schedules?award_cycle_id=`; each ID must be one of the schedules configured on
the scholarship's award cycle, and any other ID is rejected. Example: `["7HN4KX2RB"]`.
    
</dd>
</dl>

<dl>
<dd>

**$departmentId:** `?string` 

Optional stored ID of the department the scholarship is owned by. Used for Department Admin
scoping. Example: `"9TQ3HX7PD"`.
    
</dd>
</dl>

<dl>
<dd>

**$donorIds:** `?array` 

Optional list of the stored IDs of donors or donor organizations associated with the scholarship.
Example: `["2KD7RW4MA", "8PX5NT3QF"]`.
    
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

<details><summary><code>$client-&gt;scholarships-&gt;get($scholarshipId) -> ?ScholarshipV1</code></summary>
<dl>
<dd>

#### 🔌 Usage

<dl>
<dd>

<dl>
<dd>

```php
$client->scholarships->get(
    'scholarshipId',
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

**$scholarshipId:** `string` 
    
</dd>
</dl>
</dd>
</dl>


</dd>
</dl>
</details>

<details><summary><code>$client-&gt;scholarships-&gt;update($scholarshipId, $request) -> ?ScholarshipV1</code></summary>
<dl>
<dd>

#### 📝 Description

<dl>
<dd>

<dl>
<dd>

Changes a scholarship's name, application open and close dates, department, fund, donors, description,
award notification wording, custom field values and qualifications (`add_qualifications` and
`remove_qualifications`), and returns the updated scholarship in the same shape as getting it. Only the
fields in the body change: a field left out, or sent as `null`, stays as it is, and an empty string
clears a field only where the scholarship's Details tab lets it be cleared (the department, the fund and a
custom field's value).


The same rules apply as when an administrator saves the scholarship's Details tab, so anything refused there
is refused here. Every field is checked before anything is saved, and the changes are saved together or not
at all: a request with one refused field saves nothing, and the error names each refused field in
`details`. A field this call does not change (for example `total_amount`) is refused the same way.


A date sent without a time is that calendar day in the institution's time zone, and the scholarship keeps
the time of day it opens or closes at now. A scholarship that starts taking applications again because of
the change has its student matching and auto-apply run shortly after the save, as on the Details tab.

`remove_qualifications` and `add_qualifications` name groups by their `group_number` in the
scholarship's `qualification_groups` as last read, and the rules are saved by the Qualifications tab's own
save, so its checks apply. Qualifications change only while the scholarship is taking applications, and the
request is refused if either its stored close date has passed or the `application_end_date` it sends is
in the past: reopening a closed scholarship and changing its qualifications in one request is refused, so
reopen it first and change the qualifications in a second call. Students are re-checked against
changed qualifications shortly after the save. A question behind a qualification always carries its stored
ID; a question found without one is a server error (500).


A scholarship ID the institution does not have, or that is malformed, returns `404 scholarship_not_found`
naming `id`. The call needs an API key with write permission.


Set `dry_run=true` (query param or `Dry-Run: true` header) to check the changes and see the
scholarship as it would be, without saving anything.

<b>Idempotency:</b> the request supports an optional `Idempotency-Key` header (any printable-ASCII
string, 1–255 chars). The same key plus the same request body within 24 hours returns the original response
without changing the scholarship again. The same key with a different body returns
`422 idempotency_key_request_mismatch`; a concurrent retry while the first call is still executing
returns `409 idempotency_key_in_flight`. Only 2xx responses are cached.
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
$client->scholarships->update(
    'scholarshipId',
    new UpdateScholarshipV1Request([]),
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

**$scholarshipId:** `string` — The scholarship's stored ID.
    
</dd>
</dl>

<dl>
<dd>

**$name:** `?string` 

The scholarship's new name. It cannot be blank, and no other scholarship in the same award cycle may have it.
Example: `"Smith Family Nursing Scholarship"`.
    
</dd>
</dl>

<dl>
<dd>

**$applicationStartDate:** `?string` 

The new day the application window opens. A date without a time (`"2026-09-01"`) is that calendar day
in the institution's time zone, and the scholarship keeps the time of day it opens at now. A date and time
with an offset (`"2026-09-01T08:00:00-04:00"`) is used exactly as sent. It cannot be cleared, and it
must fall before the close date and inside the award cycle, as on the scholarship's Details tab.
    
</dd>
</dl>

<dl>
<dd>

**$applicationEndDate:** `?string` 

The new day the application window closes. A date without a time (`"2026-12-01"`) is that calendar day
in the institution's time zone, and the scholarship keeps the time of day it closes at now, so a
scholarship that closes at the end of the day closes at the end of the new day. A date and time with an
offset (`"2026-12-01T17:00:00-05:00"`) is used exactly as sent. It cannot be cleared, and it must fall
after the open date and inside the award cycle, as on the scholarship's Details tab.
    
</dd>
</dl>

<dl>
<dd>

**$departmentId:** `?string` 

The stored ID of the department the scholarship moves to. An empty string removes its department.
Example: `"4MZ8RT2QX"`.
    
</dd>
</dl>

<dl>
<dd>

**$fundId:** `?string` 

The fund code the scholarship is paid from, as the create call takes it, at most 128 characters. A code the
institution does not have yet adds that fund, as the Details tab does. An empty string removes the fund.
Example: `"NURS-2026"`.
    
</dd>
</dl>

<dl>
<dd>

**$addDonorIds:** `?array` 

The stored IDs of donors to link to the scholarship. Each must be a donor or organization at the institution
that is not archived and not already linked. Example: `["9QW3ZK7HD"]`.
    
</dd>
</dl>

<dl>
<dd>

**$removeDonorIds:** `?array` 

The stored IDs of donors to unlink from the scholarship. Each must be linked to it now, and none may also be
in `add_donor_ids`. Example: `["2HX6PC4MT"]`.
    
</dd>
</dl>

<dl>
<dd>

**$description:** `?string` — The scholarship's new description, replacing the whole description students see. It cannot be blank.
    
</dd>
</dl>

<dl>
<dd>

**$awardNotification:** `?UpdateScholarshipAwardNotificationV1Request` 
    
</dd>
</dl>

<dl>
<dd>

**$customFields:** `?array` 

New values for custom fields, each named by its stored ID; a field not listed keeps its value. The same rules
as the Details tab apply: a choice field takes only its active choices, and a value outside them is refused
naming the field. Each field may be listed once.
Example: `[{"id": "3KD8WQ2MZ", "value": "Merit"}, {"id": "7RT4HX9NC", "values": ["Nursing", "Biology"]}]`.
    
</dd>
</dl>

<dl>
<dd>

**$removeQualifications:** `?array` 

Qualification rules to remove, each from one group. Removals are applied before additions. A removal that
matches no rule is refused. Group numbers are the scholarship's `qualification_groups` as last read; a
group left with no rules is dropped at the end and the groups after it move up. Refused once the application
window has closed. Example: `[{"question_id": "4MZ8RT2QX", "group": 1}]`.
    
</dd>
</dl>

<dl>
<dd>

**$addQualifications:** `?array` 

Qualification rules to add, applied in list order after the removals. To change a rule, remove it and add
the new one in the same request. A question can back at most one rule per group; a scholarship can have at
most 10 groups, or 1 on AwardSpring Now. Refused once the application window has closed. Students are
re-checked in the background after the save, so their qualified state changes shortly after the call
returns, not at once.
Example: `[{"question_id": "4MZ8RT2QX", "title": "GPA 3.2 or higher", "comparison": "at_least", "value": 3.2}]`.
    
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

#### 📝 Description

<dl>
<dd>

<dl>
<dd>

Assistant capability: `springiq_get_available_scholarship_dollars`. Required permission: Scholarships (applies when called on behalf of a signed-in person).
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
$client->scholarships->listAvailableDollars(
    new ListAvailableDollarsScholarshipsRequest([
        'awardCycleId' => 'award_cycle_id',
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

**$awardCycleId:** `string` 
    
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

<dl>
<dd>

**$include_:** `?string` — Optional extras to add to the response, comma-separated. `total_count` is the only supported value: it adds `total_count`, the number of records that match this request across all pages. The count is taken fresh at the moment of each request, so it can change while you page; ask for it on the first page. Without it, `total_count` is left out of the response. Asking or not never invalidates a cursor. Any other value returns `400 validation_failed`.
    
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

#### 📝 Description

<dl>
<dd>

<dl>
<dd>

Assistant capability: `springiq_get_available_scholarship_dollars`. Required permission: Scholarships (applies when called on behalf of a signed-in person).
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
$client->scholarships->getAvailableDollars(
    'scholarshipId',
    new GetAvailableDollarsScholarshipsRequest([
        'awardCycleId' => 'award_cycle_id',
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

**$scholarshipId:** `string` 
    
</dd>
</dl>

<dl>
<dd>

**$awardCycleId:** `string` 
    
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

#### 📝 Description

<dl>
<dd>

<dl>
<dd>

Assistant capability: `springiq_get_awarded_students`. Required permission: Scholarships (applies when called on behalf of a signed-in person).
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
$client->scholarships->listAwardedStudents(
    new ListAwardedStudentsScholarshipsRequest([
        'awardCycleId' => 'award_cycle_id',
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

**$awardCycleId:** `string` 
    
</dd>
</dl>

<dl>
<dd>

**$scholarshipId:** `?string` 
    
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

<dl>
<dd>

**$include_:** `?string` — Optional extras to add to the response, comma-separated. `total_count` is the only supported value: it adds `total_count`, the number of records that match this request across all pages. The count is taken fresh at the moment of each request, so it can change while you page; ask for it on the first page. Without it, `total_count` is left out of the response. Asking or not never invalidates a cursor. Any other value returns `400 validation_failed`.
    
</dd>
</dl>
</dd>
</dl>


</dd>
</dl>
</details>

## Students
<details><summary><code>$client-&gt;students-&gt;list($request) -> ?StudentV1ListResponse</code></summary>
<dl>
<dd>

#### 📝 Description

<dl>
<dd>

<dl>
<dd>

Returns one page at a time, covering every student account at the institution — both
registered and prospective. Search with `q`: it splits on spaces and matches each
term against first name, last name, or email. Filter with `student_id` to look a
student up by the number the institution assigned; numbers are not guaranteed unique, so
the match can return more than one student.

Assistant capability: `springiq_list_students`. Required permission: Users (applies when called on behalf of a signed-in person).
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
$client->students->list(
    new ListStudentsRequest([]),
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

**$q:** `?string` — Free-text search (`?q=`): splits on spaces and matches each term against first name, last name, or email, case-insensitively.
    
</dd>
</dl>

<dl>
<dd>

**$studentId:** `?string` 

Institution-assigned student number (`?student_id=`): exact, case-insensitive
match. Student numbers are not guaranteed unique, so this can return more than one student.
    
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

<dl>
<dd>

**$include_:** `?string` — Optional extras to add to the response, comma-separated. `total_count` is the only supported value: it adds `total_count`, the number of records that match this request across all pages. The count is taken fresh at the moment of each request, so it can change while you page; ask for it on the first page. Without it, `total_count` is left out of the response. Asking or not never invalidates a cursor. Any other value returns `400 validation_failed`.
    
</dd>
</dl>
</dd>
</dl>


</dd>
</dl>
</details>

<details><summary><code>$client-&gt;students-&gt;create($request) -> ?StudentV1</code></summary>
<dl>
<dd>

#### 📝 Description

<dl>
<dd>

<dl>
<dd>

Adds one student account, with the same rules as adding a student from the Users list in the AwardSpring
admin interface: a first name, a last name and a well-formed email are required, each at most 50
characters. An optional `student_id` must not already be on another account. The new student gets the
same welcome email the admin interface sends, with a link to set a password.

<b>An email that already has an account:</b> by default the email must not already be used by any account
at the institution, ignoring upper and lower case; if it is, this returns `400 email_already_exists`.
With `if_email_in_use` set to `add_role`, this does what the admin interface does instead, and no
second account is made. When an active account at the institution has the email, the student role is added
to that account, which keeps its name, email, password and student ID (a `student_id` sent with the request
is still checked against other accounts but is not saved), and this returns `200` with that account as
the student. An account that already has the student role is returned unchanged, also with `200`. The
role is never added to an account that holds an administrator role, and this returns
`400 email_already_exists` instead, as it does for an email whose account is deactivated, merged, or one
the admin interface would not change. When only an archived account has the email, that account gets no
role: a new student is made with `201` and the email moves to them, as in the admin interface.
The `AwardSpring-Existing-Account` header says which of these happened: `role_added`,
`role_already_held` or `email_moved_from_archived`. It is absent for an ordinary create.

<b>Welcome email:</b> the response carries an `AwardSpring-Welcome-Email` header. It is `queued`
when the email is on its way, `not_sent` when it could not be sent, and `none` when the role went
onto an account that already had the email (`if_email_in_use` set to `add_role`), which gets no
welcome email. The student is created either way; for
`not_sent`, send them a set-password link with Reset Password on their account in the admin
interface. A response replayed for an `Idempotency-Key` carries neither header.

<b>Idempotency:</b> the request supports an optional `Idempotency-Key` header (any
printable-ASCII string, 1–255 chars — UUIDs work well). When supplied, the same key plus the same
request body within 24 hours returns the original response verbatim without creating a duplicate
student. The same key with a different body returns `422 idempotency_key_request_mismatch`; a
concurrent retry while the first call is still executing returns `409 idempotency_key_in_flight`.
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
$client->students->create(
    new CreateStudentV1Request([]),
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

**$firstName:** `?string` — The student's first name. Required, at most 50 characters. Example: `"Jordan"`.
    
</dd>
</dl>

<dl>
<dd>

**$lastName:** `?string` — The student's last name. Required, at most 50 characters. Example: `"Ellis"`.
    
</dd>
</dl>

<dl>
<dd>

**$email:** `?string` 

The student's email, which is also how they sign in. Required, at most 50 characters, and not already
used by any account at the institution, ignoring upper and lower case, unless `if_email_in_use` is
`add_role`. Example: `"jordan.ellis@school.edu"`.
    
</dd>
</dl>

<dl>
<dd>

**$studentId:** `?string` 

Optional. The student number the institution assigned (for example an SIS or campus id), at most 50
characters, and not already on another account at the institution. Example: `"20260142"`.
    
</dd>
</dl>

<dl>
<dd>

**$ifEmailInUse:** `?string` 

Optional. What to do when an account at the institution already has the email. `error`, the default,
returns `400 email_already_exists`. `add_role` does what the Users list's Add user window does:
the student role is added to that account instead of making a second one. Any other value returns
`400 validation_failed`.
    
</dd>
</dl>
</dd>
</dl>


</dd>
</dl>
</details>

<details><summary><code>$client-&gt;students-&gt;get($id) -> ?StudentV1</code></summary>
<dl>
<dd>

#### 📝 Description

<dl>
<dd>

<dl>
<dd>

Fetches one student by their `id` from the students list. Returns
`404 student_not_found`, naming `id` in `param`, if no such student belongs to this
institution.

Assistant capability: `springiq_get_student`. Required permission: Users (applies when called on behalf of a signed-in person).
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
$client->students->get(
    'id',
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

**$id:** `string` 
    
</dd>
</dl>
</dd>
</dl>


</dd>
</dl>
</details>

<details><summary><code>$client-&gt;students-&gt;update($id, $request) -> ?StudentV1</code></summary>
<dl>
<dd>

#### 📝 Description

<dl>
<dd>

<dl>
<dd>

Changes a student's `first_name`, `last_name`, `email`, `student_id` and `phone`,
with the same checks as changing them on the student's account in the AwardSpring admin interface. The
student is named by their `id` from the students list. The change is recorded under your API key.

<b>Only what you send changes:</b> a field left out of the body, or sent as `null`, stays as it is.
An empty `phone` or `student_id` clears it. A name or email cannot be cleared: an empty one returns
`400 validation_failed` naming the field. Any other field in the body, such as `gpa`, returns
`400 validation_failed` naming it, and nothing is saved.

<b>What is refused:</b> a `student_id` already on another account at the institution, a value longer
than its limit, or a malformed email returns `400 validation_failed` naming the field. An email any
other account at the institution has, ignoring upper and lower case and including an archived account,
returns `400 email_already_exists` naming `email`. Changing the email changes how the student
signs in, and no email is sent to them about it. A refused request saves nothing.

<b>Administrator accounts:</b> a program with an API key never changes an account that holds an
administrator role. A student who is also an administrator returns `400 validation_failed` naming
`id`; change them in AwardSpring instead.


An `id` that names no student at the institution, including a donor's or reviewer's `id`,
returns `404 student_not_found`, naming `id` in `param`. Send `dry_run` to run every
check without saving: the response shows the student as the change would leave them. The student resource
has no phone, so a phone change does not appear in the response.

<b>Idempotency:</b> the request supports an optional `Idempotency-Key` header (any
printable-ASCII string, 1–255 chars — UUIDs work well). When supplied, the same key plus the same
request body within 24 hours returns the original response verbatim without changing the student again.
The same key with a different body returns `422 idempotency_key_request_mismatch`; a concurrent retry
while the first call is still executing returns `409 idempotency_key_in_flight`.
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
$client->students->update(
    'id',
    new UpdateStudentV1Request([]),
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

**$id:** `string` — The student's id, as the students list returns it.
    
</dd>
</dl>

<dl>
<dd>

**$firstName:** `?string` 

The student's new first name, at most 250 characters. It cannot be blank: an empty value is refused rather
than clearing the name. Example: `"Jordan"`.
    
</dd>
</dl>

<dl>
<dd>

**$lastName:** `?string` 

The student's new last name, at most 250 characters. It cannot be blank: an empty value is refused rather
than clearing the name. Example: `"Ellis"`.
    
</dd>
</dl>

<dl>
<dd>

**$email:** `?string` 

The student's new email, which is also how they sign in. It must be a well-formed email of at most 250
characters, and not used by any other account at the institution, ignoring upper and lower case, including
an archived one; otherwise this returns `400 email_already_exists`. It cannot be blank. No email is
sent to the student about the change. Example: `"jordan.ellis@school.edu"`.
    
</dd>
</dl>

<dl>
<dd>

**$studentId:** `?string` 

The new student number the institution assigned (for example an SIS or campus id), at most 250 characters,
and not already on another account at the institution. An empty string clears it.
Example: `"20260142"`.
    
</dd>
</dl>

<dl>
<dd>

**$phone:** `?string` 

The student's new phone number. Everything except digits and `+` is removed before it is saved, and
what remains can be at most 100 characters. An empty string clears it. Example: `"+1 (555) 123-4567"`,
saved as `"+15551234567"`.
    
</dd>
</dl>
</dd>
</dl>


</dd>
</dl>
</details>

