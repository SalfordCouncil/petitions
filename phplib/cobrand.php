<?php
/*
 * cobrand.php:
 * Functions for different brandings of the petitions code.
 * 
 * Copyright (c) 2010 UK Citizens Online Democracy. All rights reserved.
 * Email: matthew@mysociety.org; WWW: http://www.mysociety.org
 * 
 */

# The help sentence printed under the main content of a petition's input box.
function cobrand_creation_sentence_help() {
    global $site_group, $site_name;
    $out = '(Please write a sentence';
    if ($site_group != 'surreycc') {
        $out .= ', preferably starting with a verb,';
    }
    $out .= ' that describes what action you would like ';
    $out .= OPTION_SITE_NAME=='number10' ? 'the Prime Minister or Government' : OPTION_SITE_PETITIONED;
    $out .= ' to take';
    if ($site_name != 'spelthorne')
        $out .= '.';
    $out .= ')';
    return $out;
}

function cobrand_creation_address_help() {
    global $site_name;
    if ($site_name == 'spelthorne') {
        print '<br>(Please use the address where you live, work or study in Spelthorne)';
    }
}

function cobrand_creation_deadline_limit() {
    global $site_name;
    if ($site_name == 'tandridge' || $site_name == 'surreycc')
        return array('years' => 0, 'months' => 6);
    return array('years' => 1, 'months' => 0);
}

function cobrand_creation_example_ref() {
    global $site_name;
    if ($site_name == 'spelthorne') return 'recycle';
    return 'badgers';
}
function cobrand_creation_category_first() {
    global $site_group;
    if ($site_group == 'surreycc') {
        return true;
    }
    return false;
}

function cobrand_creation_ask_for_address_type() { # by default: don't ask for address type unless it's within a specified area
    global $site_name;
    if ($site_name == 'barrowbc') return false; 
    if (cobrand_creation_within_area_only()) return true;
    if ($site_name == 'tandridge' || $site_name == 'surreyheath' || $site_name == 'suffolkcoastal') return true;
    return false;
}

# If creation should be limited to a particular area, this
# function should return a two-element array, consisting of
# the name of the area, and either an area ID that the
# creator must be within, or null if the creator can be in
# any area in the site database.
function cobrand_creation_within_area_only() {
    global $site_name;
    if ($site_name == 'surreycc') return array('Surrey', null);
    if ($site_name == 'reigate-banstead') return array('Surrey', null);
    if ($site_name == 'spelthorne') return array('Spelthorne', 2456);
    if ($site_name == 'woking') return array('Woking', 2449);
    if ($site_name == 'runnymede') return array('Runnymede', 2451);
    if ($site_name == 'waverley') return array('Waverley', 2447);
    if ($site_name == 'epsom-ewell') return array('Epsom &amp; Ewell', 2457);
    if ($site_name == 'elmbridge') return array('Elmbridge', 2455);
    if ($site_name == 'barrowbc') return array('Cumbria', 2220);
    if ($site_name == 'hounslow') return array('Hounslow', 2483);
    if ($site_name == 'islington') return array('Islington', 2507); # actually Islington requested "County Council" -- maybe meant Greater London?
    return '';
}

function cobrand_creator_must_be() {
    global $site_name;
    if ($site_name == 'surreycc' || $site_name == 'reigate-banstead')
        return 'live, work or study at a Surrey registered address';
    if ($site_name == 'spelthorne')
        return 'live, work or study in Spelthorne';
    if ($site_name == 'woking')
        return 'live, work or study in the Borough of Woking';
    if ($site_name == 'elmbridge')
        return 'live, work or study within Elmbridge (including under 18s)';
    if ($site_name == 'runnymede')
        return 'live, work or study within Runnymede';
    if ($site_name == 'waverley')
        return 'live, work or study within Waverley';
    if (cobrand_creation_within_area_only()) {
        if (cobrand_creation_ask_for_address_type()) {
            return 'live, work or study within the area of the council';
        } else {
            return 'be a council resident';
        }
    } else {
        return 'be a British citizen or resident';
    }
}

function cobrand_error_div_start() {
    global $site_name;
    if ($site_name == 'surreycc') {
        return '<div class="scc-error">';
    }
    return '<div id="errors">';
}

function cobrand_overseas_dropdown() {
    global $site_group;
    if ($site_group == 'surreycc') {
        return array(
            '-- Select --',
            'Armed Forces',
            'Non UK address'
        );
    }
    return array(
        '-- Select --',
        'Expatriate',
        'Armed Forces',
        'Anguilla',
        'Ascension Island',
        'Bermuda',
        'British Antarctic Territory',
        'British Indian Ocean Territory',
        'British Virgin Islands',
        'Cayman Islands',
        'Channel Islands',
        'Falkland Islands',
        'Gibraltar',
        'Isle of Man',
        'Montserrat',
        'Pitcairn Island',
        'St Helena',
        'S. Georgia and the S. Sandwich Islands',
        'Tristan da Cunha',
        'Turks and Caicos Islands',
    );
}

function cobrand_category_okay($category_id) {
    global $site_name, $site_group;
    if ($site_group != 'surreycc') return true;
    $county_only = array(4, 6, 7, 10, 12, 13, 16);
    if ($site_name == 'tandridge' || $site_name == 'reigate-banstead') $county_only[] = 11; # Planning not okay in Tandridge
    if ($site_name != 'surreycc' && in_array($category_id, $county_only))
        return false;
    $district_only = array(1, 2, 3, 5, 8, 9, 15);
    if ($site_name == 'surreycc' && in_array($category_id, $district_only))
        return false;
    return true;
}

function cobrand_category_wrong_action($category_id, $area='') {
    global $site_name, $site_group;
    if ($site_group == 'surreycc') {
        if ($site_name != 'surreycc') {
            if ($site_name == 'tandridge' && $category_id == 11) { # Planning
                return "You cannot create a petition about a planning
application. For further information on the Council's procedures and how you
can express your views, see the
<a href='http://www.tandridge.gov.uk/Planning/planninginteractive/default.htm'>planning
applications</a> section.";
            } elseif ($site_name == 'reigate-banstead' && $category_id == 11) { # Planning
                return "You cannot create a petition about a planning
application. For further information on the Council's procedures and how you
can express your views, see the
<a href='http://www.reigate-banstead.gov.uk/planning/'>planning
applications</a> section.";
            } else {
                $url = 'http://petitions.surreycc.gov.uk/new?tostepmain=1&category=' . $category_id;
                return "You are petitioning about something which isn't the
responsibility of your district council, but instead of Surrey County Council.
<a href='$url'>Go to Surrey County Council's petition website to create a
petition in this category</a>."; 
            }
        }
        if ($area) {
            # $area is set if we're being called as a result of the form below
            if (in_array($area, array('tandridge', 'reigate-banstead')))
                return 'http://petitions.' . $area . '.gov.uk/new?tostepmain=1&category=' . $category_id;
            if ($area == 'elmbridge')
                return 'http://www.elmbridge.gov.uk/Council/information/petition.htm';
            if ($area == 'epsom-ewell')
                return 'http://www.epsom-ewell.gov.uk/EEBC/Council/E-petitions.htm';
            if ($area == 'guildford')
                return 'http://www.surreycc.gov.uk/SCCWebsite/SCCWSPages.nsf/LookupWebPagesByUNID_RTF_INT/A4F9AD1334EF7EB480257744005476BA?opendocument';
            if ($area == 'molevalley')
                return 'http://www.molevalley.gov.uk/index.cfm?articleid=9694';
            if ($area == 'spelthorne')
                return 'http://www.spelthorne.gov.uk/epetitions.htm';
            if ($area == 'runnymede')
                return 'http://www.runnymede.gov.uk/portal/site/runnymede/menuitem.bbcf55f3a4a758ceb14229a7af8ca028/';
            if ($area == 'surreyheath')
                return 'http://www.surreyheath.gov.uk/council/epetitions/';
            if ($area == 'waverley')
                return 'http://www.waverley.gov.uk/site/scripts/documents_info.php?documentID=955';
            if ($area == 'woking')
                return 'http://www.woking.gov.uk/council/about/epetitions';
        } else {
            return '
            <input type="hidden" name="category" value="' . $category_id . '"> 
            You are petitioning about something which isn\'t the responsibility of Surrey Council Council,
            but instead of your district council. <label for="council_pick">Please
            pick your district council in order to be taken to their petition site:</label>
            <select name="council" id="council_pick">
            <option value="elmbridge">Elmbridge Borough Council</option>
            <option value="epsom-ewell">Epsom and Ewell Borough Council</option>
            <option value="guildford">Guildford Borough Council</option>
            <option value="molevalley">Mole Valley District Council</option>
            <option value="reigate-banstead">Reigate &amp; Banstead Borough Council</option> 
            <option value="runnymede">Runnymede Borough Council</option>
            <option value="spelthorne">Spelthorne Borough Council</option>
            <option value="surreyheath">Surrey Heath Borough Council</option> 
            <option value="tandridge">Tandridge District Council</option> 
            <option value="waverley">Waverley Borough Council</option> 
            <option value="woking">Woking Borough Council</option> 
            </select> 
            <input type="submit" name="toothercouncil" value="Go" class="button">
            '; 
        }
    }
    return null;
}

function cobrand_categories() {
    global $site_group;
    if ($site_group == 'surreycc') {
        return array(
            1 => 'Building Regulations',
            2 => 'Community safety',
            3 => 'Council Tax Collection',
            4 => 'Education',
            5 => 'Environmental Health',
            6 => 'Fire & Rescue',
            7 => 'Highways',
            8 => 'Housing',
            9 => 'Leisure and Recreation',
            10 => 'Libraries',
            11 => 'Planning Applications', # Both?
            12 => 'Social Services',
            13 => 'Transport and Travel',
            14 => 'Trading Standards', # Both?
            15 => 'Waste Collection',
            16 => 'Waste Disposal',
            99 => 'Other', # Both
        );
    }

    global $global_petition_categories;
    return $global_petition_categories;
}

function cobrand_category($id) {
    $categories = cobrand_categories();
    return $categories[$id];
}

function cobrand_signature_threshold() {
    global $site_name;
    if ($site_name == 'number10') return 500;
    if ($site_name == 'surreycc') return 100;
    if (in_array($site_name, array('woking', 'tandridge'))) return 10;
    if (in_array($site_name, array('surreyheath', 'waverley', 'runnymede'))) return 50;
    return 100;
}

# This function could be run from cron, so can't just use site_name
function cobrand_site_group() {
    if (strpos(OPTION_SITE_NAME, ',')) {
        $sites = explode(',', OPTION_SITE_NAME);
        $site_group = $sites[0];
    } else {
        $site_group = OPTION_SITE_NAME;
    }
    return $site_group;
}

function cobrand_admin_title() {
    global $site_group;
    if ($site_group == 'surreycc') {
        $sites = explode(',', OPTION_SITE_NAME);
        if (in_array(http_auth_user(), $sites))
            return ucfirst(http_auth_user()) . ' admin';
    }
    return OPTION_CONTACT_NAME . " admin";
}

function cobrand_admin_rejection_snippets() {
    global $site_group;
    $snippets = array(
'Please supply full name and address information.',
'Please address the excessive use of capital letters; they make your petition hard to read.',
'Your title should be a clear call for action, preferably starting with a verb, and not a name or statement.',
    );
    if ($site_group != 'number10') {
        return $snippets;
    }
    array_push($snippets,
'Comments about the petitions system should be sent to ' . OPTION_CONTACT_EMAIL . '.',
'Individual legal cases are a matter for direct communication with the Home Office.',
'This is a devolved matter and should be directed to the Scottish Executive / Welsh Assembly / Northern Ireland Executive as appropriate.',
'This is a matter for direct communication with Parliament.',
'The Cabinet Office is actively seeking nominations for honours from the public. Please go to http://www.direct.gov.uk/honours'
    );
    return $snippets;
}

function cobrand_admin_rejection_categories() {
    global $global_rejection_categories, $site_group;
    if ($site_group == 'number10') {
        return $global_rejection_categories;
    }
    $categories = $global_rejection_categories;
    unset($categories[65536]); # Links to websites
    return $categories;
}

function cobrand_admin_site_restriction() {
    global $site_group;
    if ($site_group != 'surreycc') return '';

    $sites = explode(',', OPTION_SITE_NAME);
    if (in_array(http_auth_user(), $sites))
        return " AND body.ref='" . http_auth_user() . "' ";
    return '';
}

function cobrand_admin_allow_html_response() {
    global $site_name;
    if ($site_name == 'number10') return true;
    return false;
}

function cobrand_admin_areas_of_interest() {
    if (OPTION_SITE_NAME == 'sbdc' || OPTION_SITE_NAME == 'sbdc1') {
        return json_decode(file_get_contents('http://mapit.mysociety.org/areas/LBO,MTD,LGD,DIS,UTA,COI'), true);
    }
    if (OPTION_SITE_NAME == 'lichfielddc') {
        return array(
            2434 => array( 'name' => 'Lichfield District Council', 'parent' => 2240 ),
            2240 => array( 'name' => 'Staffordshire County Council' ),
        );
    }

    if (cobrand_site_group() != 'surreycc') return null;

    $out = json_decode(file_get_contents('http://mapit.mysociety.org/area/2242/covers?type=DIS'), true);
    foreach ($out as $k => $v) {
        $out[$k]['parent'] = 2242;
    }
    $out[2242] = array( 'name' => 'Surrey County Council' );
    return $out;
}

# A bit of a yucky function, containing slightly varying guidelines
# for displaying at last stage of petition creation process.
function cobrand_petition_guidelines() {
    global $site_group, $site_name;
    if ($site_name == 'tandridge') {
?>

<p>The petition must refer to a matter that is relevant to the functions of a district council. Petitions submitted to the council must include: </p>

<ul>
<li>The title or subject of the petition. </li>
<li>A clear and concise statement covering the subject of the petition. </li>
<li>It should state what action the petitioner wishes the council to take. The petition will be returned to you to edit if it is unclear what action is being sought.</li>
<li>The petition author's contact address (this will not be placed on the website); </li>
<li>A duration for the petition ie the deadline you want people to sign by (maximum of six months).</li>
<li>10 signatures or more for a petition to be referred to the committee or council meeting (if less than 10 signatures, please see <a href="http://www.tandridge.gov.uk/faq/faq.htm?mode=20&amp;pk_faq=478">What if my petitions does not have 10 signatures).</a></li>
</ul>

<h4>What is not allowed?</h4>
<p>A petition will not be accepted where: </p>

<ul>
<li>It is considered to be vexatious, abusive or otherwise inappropriate. </li>
<li>It does not follow the guidelines set out above.</li>
<li>It refers to a development plan, or specific planning matter, including planning applicants.</li>
<li>It refers to a decision for which there is an existing right of appeal. </li>
<li>It is a duplicate or near duplicate of a similar petition received or submitted within the previous 12 months. </li>
<li>It refers to a specific licensing application.</li>
</ul>

<p>The information in a petition must be submitted in good faith. For the petition service to comply with the law, you must not include: </p>
<ul>
<li>Party political material. This does not mean it is not permissible to petition on controversial issues. For example, this party political petition would not be permitted: &quot;we petition the council to change the conservative adminstration's policy on housing&quot;, but this non-party political version would be: &quot;we petition the council to change its policy on housing&quot;. </li>
<li>Potentially libellous, false, or defamatory statements. </li>
<li>Information which may be protected by an injunction or court order (for example, the identities of children in custody disputes).</li>
<li>Material which is potentially confidential, commercially sensitive, or which may cause personal distress or loss.</li>
<li>Any commercial endorsement, promotion of any product, service or publication.</li>
<li>The names of individual officials of public bodies, unless they are part of the senior management of those organisations.</li>
<li>The names of family members of elected representatives or officials of public bodies.</li>
<li>The names of individuals, or information where they may be identified, in relation to criminal accusations.</li>
<li>Language which is offensive, intemperate, or provocative. This not only includes swear words and insults, but any language which people could reasonably take offence to. </li>
</ul>

<p>Further information on the Council's procedures and how you can express your views are available here: </p>
<ul>
<li><a href="http://www.tandridge.gov.uk/Planning/planninginteractive/default.htm" title="Planning online">Planning applications</a></li>
<li><a href="http://www.tandridge.gov.uk/YourCouncil/consultation.htm" title="Consultation">Consultation</a></li>
</ul>

<h4>Why might we reject your petition?</h4>
<p>Petitions which do not follow the guidelines above cannot be accepted. In these cases, you will be informed in writing of the reason(s) your petition has been refused. If this happens, we will give you the option of altering and resubmitting the petition so it can be accepted.</p>
<p>If you decide not to resubmit your petition, or if the second one is also rejected, we will list your petition and the reason(s) for not accepting it on this website. We will publish the full text of your petition, unless the content is illegal or offensive. </p>
<p>We reserve the right to reject: </p>

<ul>
<li>Petitions similar to and/or overlap with an existing petition or petitions.</li>
<li>Petitions which ask for things outside the remit or powers of the council. </li>
<li>Statements that don't request any action. We cannot accept petitions which call upon the council to &quot;recognise&quot; or &quot;acknowledge&quot; something, as they do not call for a recognisable action. </li>
<li>Wording that is impossible to understand. Please don't use capital letters excessively as they can make petitions hard to read. </li>
<li>Statements that amount to advertisements.</li>
<li>Petitions intended to be humorous, or which have no point about council policy.</li>
<li>Issues for which an e-petition is not the appropriate channel (for example, correspondence about a personal issue).</li>
<li>Freedom of Information requests. This is not the right channel for FOI requests - <a href="http://www.tandridge.gov.uk/YourCouncil/DataProtectionFreedomofInformation/freedom_of_information.htm" title="Freedom of information ">Freedom of information.</a></li>
</ul>

<p><a href="/terms">Full terms and conditions</a></p>

<?
    } elseif ($site_group == 'surreycc') {
        $foi_link = 'http://www.ico.gov.uk/';
        $foi_text = $foi_link;
        if ($site_name == 'reigate-banstead') {
            $foi_link = 'http://www.reigate-banstead.gov.uk/council_and_democracy/about_the_council/access_to_information/freedom_of_information_act_2000/';
            $foi_text = 'Freedom Of Information Act 2000';
        }
?>

<p>
The information in a petition must be submitted in good faith. In
order for the petition service to comply with the law,
you must not include: </p>

<ul>
<li>Party political material.
Please note, this does not mean it is not permissible to petition on
controversial issues. For example, this party political petition
would not be permitted: "We petition the council to change the Conservative Cabinet's policy on education",
but this non-party political version would be:
"We petition the council to change their policy on education".</li>
<li>potentially libellous, false, or defamatory statements;</li>
<li>information which may be protected by an injunction or court order (for
example, the identities of children in custody disputes);</li>
<li>material which is potentially confidential, commercially sensitive, or which
may cause personal distress or loss;</li>
<li>any commercial endorsement, promotion of any product, service or publication;</li>
<li>the names of individual officials of public bodies, unless they
are part of the senior management of those organisations;</li>
<li>the names of family members of elected representatives or
officials of public bodies;</li>
<li>the names of individuals, or information where they may be
identified, in relation to criminal accusations;</li>
<li>language which is offensive, intemperate, or provocative. This not
only includes obvious swear words and insults, but any language to which
people reading it could reasonably take offence (we believe it is
possible to petition for anything, no matter how radical, politely).</li>
</ul>

<p>We reserve the right to reject:</p>
<ul>
<li>petitions that are similar to and/or overlap with an existing petition or petitions;</li>
<li>petitions which ask for things outside the remit or powers of the council</li>
<li>statements that don't actually request any action - ideally start the title of your petition with a verb;</li>
<li>wording that is impossible to understand;</li>
<li>statements that amount to advertisements;</li>
<li>petitions which are intended to be humorous, or which
have no point about council policy (however witty these
are, it is not appropriate to use a publically-funded website
for purely frivolous purposes);</li>
<li>issues for which an e-petition is not the appropriate channel
(for example, correspondence about a personal issue);</li>
<li>Freedom of Information requests. This is not the right channel
for FOI requests; information about the appropriate procedure can be
found at <a href="<?=$foi_link?>" target="_blank"><?=$foi_text?> <small>(new window)</small></a>.</li>
</ul>

<p>We will strive to ensure that petitions that do not meet our
criteria are not accepted, but where a petition is accepted which
contains misleading information we reserve the right to post an
interim response to highlight this point to anyone visiting to 
sign the petition.</p>

<h2>Common causes for rejection</h2>

<p>In order to help you avoid common problems, we've produced this list:</p>

<ul>

<li>Please don't use 'shouting' capital letters excessively as they
can make petitions fall foul of our 'impossible to read' criteria.</li>

<li>We cannot accept petitions which call upon the council to "recognise" or
"acknowledge" something, as they do not clearly call for a
recognisable action.</li>

</ul>

<?
    } elseif ($site_group == 'number10') {
?>

<p>
The information in a petition must be submitted in good faith. In
order for the petition service to comply with the law and with
the Civil Service Code, you must not include: </p>

<ul>
<li>Party political material. This website is a
Government site. Party political content cannot be published, under the
<a href="http://www.civilservice.gov.uk/civilservicecode">normal rules governing the Civil Service</a>.
Please note, this does not mean it is not permissible to petition on
controversial issues. For example, this party political petition
would not be permitted: "We petition the PM to change his party's policy on education",
but this non-party political version would be:
"We petition the PM to change the government's policy on education".</li>
<li>potentially libellous, false, or defamatory statements;</li>
<li>information which may be protected by an injunction or court order (for
example, the identities of children in custody disputes);</li>
<li>material which is potentially confidential, commercially sensitive, or which
may cause personal distress or loss;</li>
<li>any commercial endorsement, promotion of any product, service or publication;</li>
<li>URLs or web links (we cannot vet the content of external sites, and
therefore cannot link to them from this site);</li>
<li>the names of individual officials of public bodies, unless they
are part of the senior management of those organisations;</li>
<li>the names of family members of elected representatives or
officials of public bodies;</li>
<li>the names of individuals, or information where they may be
identified, in relation to criminal accusations;</li>
<li>language which is offensive, intemperate, or provocative. This not
only includes obvious swear words and insults, but any language to which
people reading it could reasonably take offence (we believe it is
possible to petition for anything, no matter how radical, politely).</li>
</ul>

<p>We reserve the right to reject:</p>
<ul>
<li>petitions that are similar to and/or overlap with an existing petition or petitions;</li>
<li>petitions which ask for things outside the remit or powers of the Prime Minister and Government</li>
<li>statements that don't actually request any action - ideally start the title of your petition with a verb;</li>
<li>wording that is impossible to understand;</li>
<li>statements that amount to advertisements;</li>
<li>petitions which are intended to be humorous, or which
have no point about government policy (however witty these
are, it is not appropriate to use a publically-funded website
for purely frivolous purposes);</li>
<li>issues for which an e-petition is not the appropriate channel
(for example, correspondence about a personal issue);</li>
<li>Freedom of Information requests. This is not the right channel
for FOI requests; information about the appropriate procedure can be
found at <a href="http://www.ico.gov.uk/">http://www.ico.gov.uk/</a>.</li>
<li>nominations for Honours. These have been accepted in the past but
this is not the appropriate channel; accordingly, from 6 March 2008 we
are rejecting such petitions and directing petitioners to
<a href="http://www.direct.gov.uk/honours">http://www.direct.gov.uk/honours</a> where
nominations for Honours can be made directly to the appropriate department.</li>
</ul>

<p>We will strive to ensure that petitions that do not meet our
criteria are not accepted, but where a petition is accepted which
contains misleading information we reserve the right to post an
interim response to highlight this point to anyone visiting to 
sign the petition.</p>

<h3>Common causes for rejection</h3>

<p>Running the petition site, we see a lot of people having petitions
rejected for a handful of very similar reasons. In order to help you
avoid common problems, we've produced this list:</p>

<ul>
<li>We don't accept petitions on individual legal cases such as
deportations because we can never ascertain whether the individual
involved has given permission for their details to be made publicly
known. We advise petitioners to take their concerns on such matters
directly to the Home Office.</li>

<li>Please don't use 'shouting' capital letters excessively as they
can make petitions fall foul of our 'impossible to read' criteria.</li>

<li>We receive a lot of petitions on devolved matters. If your
petition relates to the powers devolved to parts of the UK, such as
the Welsh Assembly or Scottish Parliament, you should approach those
bodies directly as these things are outside the remit of the Prime
Minister. This also applies to matters relating to London, such as
the Underground, which should be directed to the Greater London
Assembly and the Mayor's Office.</li>

<li>We also receive petitions about decisions that are clearly private
sector decisions, such as whether to re-introduce a brand of breakfast
cereal. These are also outside the remit of the Prime Minister.</li>

<li>We cannot accept petitions which call upon <?=OPTION_SITE_PETITIONED?> to "recognise" or
"acknowledge" something, as they do not clearly call for a
recognisable action.</li>

</ul>

<?
    } else {
?>

<p>
The information in a petition must be submitted in good faith. In
order for the petition service to comply with the law,
you must not include:</p>

<ul>
<li>Party political material.
Please note, this does not mean it is not permissible to petition on
controversial issues. For example, this party political petition
would not be permitted: "We petition <?=OPTION_SITE_PETITIONED ?> to change the Labour executive's policy on education",
but this non-party political version would be:
"We petition <?=OPTION_SITE_PETITIONED ?> to change their policy on education".</li>
<li>potentially libellous, false, or defamatory statements;</li>
<li>information which may be protected by an injunction or court order (for
example, the identities of children in custody disputes);</li>
<li>material which is potentially confidential, commercially sensitive, or which
may cause personal distress or loss;</li>
<li>any commercial endorsement, promotion of any product, service or publication;</li>
<li>URLs or web links (we cannot vet the content of external sites, and
therefore cannot link to them from this site);</li>
<li>the names of individual officials of public bodies, unless they
are part of the senior management of those organisations;</li>
<li>the names of family members of elected representatives or
officials of public bodies;</li>
<li>the names of individuals, or information where they may be
identified, in relation to criminal accusations;</li>
<li>language which is offensive, intemperate, or provocative. This not
only includes obvious swear words and insults, but any language to which
people reading it could reasonably take offence (we believe it is
possible to petition for anything, no matter how radical, politely).</li>
</ul>

<?
        if (OPTION_SITE_APPROVAL) {
?>

<p>We reserve the right to reject:</p>
<ul>
<li>petitions that are similar to and/or overlap with an existing petition or petitions;</li>
<li>petitions which ask for things outside the remit or powers of <?=OPTION_SITE_PETITIONED ?>;</li>
<li>statements that don't actually request any action - ideally start the title of your petition with a verb;</li>
<li>wording that is impossible to understand;</li>
<li>statements that amount to advertisements;</li>
<li>petitions which are intended to be humorous, or which
have no point about government policy (however witty these
are, it is not appropriate to use a publically-funded website
for purely frivolous purposes);</li>
<li>issues for which an e-petition is not the appropriate channel
(for example, correspondence about a personal issue);</li>
<li>Freedom of Information requests. This is not the right channel
for FOI requests; information about the appropriate procedure can be
found at <a href="http://www.ico.gov.uk/">http://www.ico.gov.uk/</a>.</li>
</ul>

<p>We will strive to ensure that petitions that do not meet our
criteria are not accepted, but where a petition is accepted which
contains misleading information we reserve the right to post an
interim response to highlight this point to anyone visiting to 
sign the petition.</p>

<h3>Common causes for rejection</h3>

<p>In order to help you avoid common problems, we've produced this list:</p>

<ul>
<li>We don't accept petitions on individual legal cases such as
deportations because we can never ascertain whether the individual
involved has given permission for their details to be made publicly
known. We advise petitioners to take their concerns on such matters
directly to the Home Office.</li>

<li>Please don't use 'shouting' capital letters excessively as they
can make petitions fall foul of our 'impossible to read' criteria.</li>

<li>We cannot accept petitions which call upon <?=OPTION_SITE_PETITIONED?> to "recognise" or
"acknowledge" something, as they do not clearly call for a
recognisable action.</li>

</ul>

<?
        } else {
            print '<p>Petitions which are found to break these terms will be removed from the site.</p>';
        }

    }
}

# If a body has their own explanation of RSS, this function returns it;
# otherwise the BBC RSS help page.
function cobrand_rss_explanation_link() {
    global $site_name;
    if ($site_name == 'surreycc')
        return 'http://www.surreycc.gov.uk/sccwebsite/sccwspages.nsf/LookupWebPagesByTITLE_RTF/RSS+feeds?opendocument';
    return 'http://news.bbc.co.uk/1/hi/help/3223484.stm';
}

# If a body hosts their own T&Cs page, this function returns its location
function cobrand_terms_elsewhere() {
    global $site_name;
    if ($site_name == 'surreycc')
        return 'http://www.surreycc.gov.uk/sccwebsite/sccwspages.nsf/LookupWebPagesByTITLE_RTF/Terms+and+conditions+for+petitions?opendocument';
    if ($site_name == 'tandridge')
        return 'http://www.tandridge.gov.uk/YourCouncil/CouncillorsMeetings/petitions/terms.htm';
    if ($site_name == 'reigate-banstead')
        return 'http://www.reigate-banstead.gov.uk/council_and_democracy/local_democracy/petitions/tcpetitions/index.asp';
    if ($site_name == 'spelthorne')
        return 'http://www.spelthorne.gov.uk/petitions_terms';
    if ($site_name == 'woking')
        return 'http://www.woking.gov.uk/council/about/petitions/termsandconditions';
    return null;
}

function cobrand_steps_elsewhere() {
    global $site_name;
    if ($site_name == 'surreycc')
        return 'http://www.surreycc.gov.uk/sccwebsite/sccwspages.nsf/LookupWebPagesByTITLE_RTF/Step+by+step+guide+to+e-petitions?opendocument';
    if ($site_name == 'reigate-banstead')
        return 'http://www.reigate-banstead.gov.uk/council_and_democracy/local_democracy/petitions/stepbystep/index.asp';
    if ($site_name == 'spelthorne')
        return 'http://www.spelthorne.gov.uk/petitions_guide';
    return null;
}

function cobrand_main_heading($text) {
    global $site_name;
    if ($site_name == 'surreycc')
        return "<h2>$text</h2>";
    elseif ($site_name == 'number10')
        return "<h3 class='page_title_border'>$text</h3>";
    return "<h3>$text</h3>";
}

function cobrand_create_heading($text) {
    global $site_name;
    if ($site_name == 'reigate-banstead')
        return "<h3>$text</h3>";
    elseif ($site_name == 'number10')
        return "<h2 class='page_title_border'>$text</h2>";
    return "<h2>$text</h2>";
}

# Currently used on creation and list pages to supply a
# main heading that one council asked for.
function cobrand_extra_heading($text) {
    global $site_name;
    if ($site_name == 'tandridge' || $site_name == 'molevalley')
        print "<h1>$text</h1>";
}

function cobrand_allowed_responses() {
    global $site_name;
    if ($site_name == 'surrey' || $site_name == 'tandridge' || $site_name == 'number10')
        return 2;
    return 8;
}
