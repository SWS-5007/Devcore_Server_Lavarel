<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml"
      xmlns:o="urn:schemas-microsoft-com:office:office" lang="en" xml:lang="en">
<head>

    <title style="box-sizing: border-box;">DevCore Email</title>
    <!--[if gte mso 9]>
    <xml>
        <o:OfficeDocumentSettings>
            <o:AllowPNG />
            <o:PixelsPerInch>96</o:PixelsPerInch>
        </o:OfficeDocumentSettings>
    </xml>
    <![endif]-->
    <style>
        @media only screen and (max-width: 480px) {
            *[class~=hide_on_mobile] {
                display: none !important;
            }

            *[class~=show_on_mobile] {
                display: block !important;
                width: auto !important;
                max-height: inherit !important;
                overflow: visible !important;
                float: none !important;
                display: none;
                font-size: 0;
                max-height: 0;
                line-height: 0;
                mso-hide: all;
            }
        }
    </style>
    <style>

        @media only screen and (max-device-width: 480px) {
            .hide { max-height: none !important; font-size: 12px !important; display: block !important; }
        }


            /* CSS for hiding content in desktop/webmail clients */
        .hide { max-height: 0px; font-size: 0; display: none; }



        html,
        body {
            margin: 0 auto !important;
            padding: 0 !important;
            height: 100% !important;
            width: 100% !important;
        }

        * {
            -ms-text-size-adjust: 100%;
        }

        table,
        td {
            mso-table-lspace: 32px !important;
            mso-table-rspace: 32px !important;
        }

        img {
            -ms-interpolation-mode: bicubic;
        }
        a {
            text-decoration: none;
        }


    </style>
</head>


<body style="text-align: center; width:100%; margin: 0; padding: 0 !important; mso-line-height-rule: exactly;">

    <tr>

        <td class="hide_on_mobile"></td>
        <td style="width: 80%;">
            <div

                style="box-sizing: border-box;margin: 0;font-family: -apple-system,BlinkMacSystemFont,&quot;Segoe UI&quot;,Roboto,&quot;Helvetica Neue&quot;,Arial,sans-serif,&quot;Apple Color Emoji&quot;,&quot;Segoe UI Emoji&quot;,&quot;Segoe UI Symbol&quot;,&quot;Noto Color Emoji&quot;;font-size: 1rem;font-weight: 400;line-height: 1.5;color: #212529;text-align: left;background-color: #fff;">
                <nav class="navbar navbar-light bg-light main-header"
                     style="box-sizing: border-box;display: none;position: relative;-ms-flex-wrap: wrap;flex-wrap: wrap;-ms-flex-align: center;align-items: center;-ms-flex-pack: justify;justify-content: space-between;padding: .5rem 1rem;color: #fff;border-radius: 5px;background-color: #000 !important;">
                    <div class="row"
                         style="text-align:center;background:#000;color:#fff;box-sizing: border-box;display: flex;-ms-flex-wrap: wrap;flex-wrap: wrap;width:100%">
                        <table style="background-color:black;width: 100%;table-layout: fixed;" class="nomob">
                            <tr>
                                <td style="width: 10%;text-align:center">
                                    <div class="col-md-2"
                                         style="box-sizing: border-box;position: relative;min-height: 1px;-ms-flex: 0 0 16.666667%;flex: 0 0 16.666667%;margin-top: auto;margin-bottom: auto;">
                                        <div class="navbar-brand"
                                             style="padding-right: 8px; color: #fff;box-sizing: border-box;text-decoration: underline;background-color: transparent;-webkit-text-decoration-skip: objects;display: inline-block;padding-top: .3125rem;padding-bottom: .3125rem;margin-right: 1rem;font-size: 1.25rem;line-height: inherit;white-space: nowrap;height:100%;"
                                             href="#">
                                            <img src="https://i.imgur.com/AaEFaKm.png" width="50" height="50"
                                                 class="d-inline-bloc0" alt=""
                                                 style="box-sizing: border-box;bmiddler-style: none;page-break-inside: avoid;display: inline-block!important;">
                                        </div>
                                    </div>
                                </td>
                                <td style="text-align: left;">
                                    <div class="col-md-10"
                                         style=" padding-top: .3125rem; padding-bottom: .3125rem;box-sizing: border-box;position: relative;min-width: 100%;min-height: 1px;padding-right: 15px;-ms-flex: 0 0 83.333333%;flex: 0 0 83.333333%;">

                                        {{Lang::get('messages.helo')}}

                                        <strong style="box-sizing: border-box;font-weight: bolder;">

                                            {{Lang::get('messages.name', ['name' => $user->first_name])}}

                                        </strong><br style="box-sizing: border-box;">
                                        <strong style="box-sizing: border-box;font-weight: bolder;">

                                            {{Lang::get('messages.weeklyDigest')}}

                                        </strong>
                                        {{Lang::get('messages.summaryFromLastWeek')}}

                                    </div>
                                </td>
                            </tr>
                        </table>
                    </div>
                </nav>
                <div style="margin: 1em;box-sizing: border-box;">
                    <div style="box-sizing: border-box;">
                        <p class="text-center p-5"
                           style="text-align:center;padding-top: 1rem !important;font-size: 14px;box-sizing: border-box;margin-top: 0;margin-bottom: 1rem;orphans: 3;widows: 3;text-align: center!important;">
                            {{Lang::get('messages.whatsNew')}}
                        </p>

                        @isset($data)
                            @foreach ($data as $processes)
                                    <table style="width:100%;">
                                        <tr>
                                            @foreach ($processes as $key => $process)
                                                <td style="width: 33.3%; font-size:medium"><a
                                                        href="#sec<?= $process->process_id ?>"
                                                        style="color: #fff;text-decoration: unset;"><button type="button"
                                                                                                            class="btn btn-primary btn-lg btn-block"
                                                                                                            style="cursor:pointer;background: #4294D0;box-sizing: border-box;border-radius: .3rem;margin: 0;font-family: inherit;font-size: 0.75rem;line-height: 1.5;overflow: visible;text-transform: none;-webkit-appearance: button;display: block;font-weight: 400;text-align: center;white-space: nowrap;vertical-align: middle;-webkit-user-select: none;-moz-user-select: none;-ms-user-select: none;user-select: none;border: 1px solid transparent;padding: .5rem 1rem;transition: color .15s ease-in-out,background-color .15s ease-in-out,border-color .15s ease-in-out,box-shadow .15s ease-in-out;color: #fff;background-color: #4294D0;border-color: #4294D0;width: 100%;cursor:pointer !important">
                                                            {{$process->process_title}}
                                                        </button></a></td>
                                                <?php if (fmod($key + 1, 3) == 0) { ?>
                                        </tr>
                                        <tr>
                                            <?php }  ?>
                                            @endforeach
                                        </tr>
                                    </table>


                                @foreach ($processes as $process)
                                    <a id="_sec<?= $process->process_id ?>" name="sec<?= $process->process_id ?>">
                                        <div>
                                            @isset($process->processIdeas)
                                                <div name="new-ideas">

                                                    <div style="text-align:center;width: 100%;padding-top:2em;padding-bottom:1em">
                                                        <table style="width:100%;table-layout: fixed;">
                                                            <tr>
                                                                <td style="width:36%;text-align: left;">
                                                        <span
                                                            style="font-size: 18px;font-weight: 700; min-width: max-content;">
                                                            {{$process->process_title}}
                                                            Ideas</span>
                                                                </td>
                                                                <td style="width:50%;text-align: center;">
                                                                    <hr
                                                                        style="width:100%;margin-right:0.5em;margin-left:0.5em;height: 1px;margin-top: 13px;color: black;">
                                                                </td>
                                                                <td style="width: 24%;text-align: right;">
                                                        <span
                                                            style="padding-right: 2px;font-size: 14px !important;min-width: max-content;font-weight: 300;padding-top: 3px;">(

                                                            {{count($process->processIdeas)}}
                                                            {{Lang::get('messages.Ideas')}}

                                                            )
                                                        </span>
                                                                </td>
                                                            </tr>
                                                        </table>
                                                    </div>
                                                    @foreach($process->processIdeas as $idea)
                                                        @if(isset($idea->idea_path[0]))

                                                           <div style="box-sizing: border-box; padding-bottom:10px">
                                                            <div class="row main-header"
                                                                 style="text-align: center; margin: 0em;background-color: #263138 !important;box-sizing: border-box;display: flex;-ms-flex-wrap: wrap;flex-wrap: wrap;color: #fff;">
                                                                <table
                                                                    style="width:100%;table-layout: fixed;background-color:#263138;border-radius: 5px;padding: 0px 10px;">
                                                                    <tr>
                                                                        <td style="width: 12%;text-align:center">
                                                                            <div class="col-md-1"
                                                                                 style="box-sizing: border-box;position: relative;min-height: 1px;-ms-flex: 0 0 8.333333%;flex: 0 0 8.333333%;">
                                                                                <a class="navbar-brand"
                                                                                   style="color: #fff;box-sizing: border-box;text-decoration: underline;background-color: transparent;-webkit-text-decoration-skip: objects;display: inline-block;font-size: 1.25rem;line-height: inherit;white-space: nowrap;height:100%;"
                                                                                   href="#">
                                                                                    <img src="https://i.imgur.com/v9hoRsN.png"
                                                                                         width="50" height="50" class="d-inline-block"
                                                                                         alt="" style="padding: 8px 8px 0px 8px;box-sizing:content-box;border-style: none;page-break-inside: avoid;display: inline-block!important;
                                                        ">
                                                                                </a>
                                                                            </div>
                                                                        </td>
                                                                        <td style="text-align: left;">
                                                                            <?php $date = date_parse($idea->idea_created_at); ?>
                                                                            <div class="col-md-10"
                                                                                 style="box-sizing: border-box;position: relative;width: 100%;min-height: 1px;padding-right: 15px;-ms-flex: 0 0 83.333333%;flex: 0 0 83.333333%;">
                                                                                <table style="color: #fff;">
                                                                                    <tr>
                                                                                        <p class="mb-0"
                                                                                           style="font-size: x-small;box-sizing: border-box;margin-top: 0;margin-bottom: 0!important;line-height:1.25;">
                                                                                            {{$date['hour'] . ':' . $date['minute'] . '-' . $date['day'] .'/'.$date['month'] .'/'. $date['year']}}
                                                                                        </p>
                                                                                    </tr>
                                                                                    <tr>
                                                                                        <strong
                                                                                            style="box-sizing: border-box;font-weight: bolder;line-height:1.25;">
                                                                                            {{$idea->idea_title}}

                                                                                        </strong>
                                                                                    </tr>
                                                                                    <tr>
                                                                                        <td width="100%">
                                                                                    @if(isset($idea->idea_path[0]))


                                                                                            <p class="mb-0"
                                                                                               style="font-size: x-small;box-sizing: border-box;margin-top: 0;margin-bottom: 0!important;line-height: 1.25;">
                                                                                                {{$idea->idea_path[0]}}

                                                                                            </p>
                                                                                    @else
                                                                                            <p class="mb-0"
                                                                                               style="font-size: x-small;box-sizing: border-box;margin-top: 0;margin-bottom: 0!important;line-height: 1.25;">
                                                                                                {{Lang::get('messages.removed')}}
                                                                                            </p>
                                                                                    @endif
                                                                                        </td>

                                                                                    </tr>


                                                                                </table>
                                                                            </div>
                                                                        </td>
                                                                    </tr>
                                                                </table>
                                                            </div>
                                                            @if ($idea->idea_desc)
                                                                <div class="collapse" id="collapseExample"
                                                                     style="text-align: center; box-sizing: border-box;display: block;">
                                                                    <table style="width: 100%;">
                                                                        <tr>
                                                                            <div class="card card-body"
                                                                                 style="text-align: left; display: inline-block;min-height: 8.5em;box-sizing: border-box;position: relative;-ms-flex-direction: column;flex-direction: column;min-width: 100%;word-wrap: break-word;background-color: #fff;background-clip: border-box;border: 1px solid rgba(0,0,0,.125);border-radius: .25rem;-ms-flex: 1 1 auto;flex: 1 1 auto;padding: 1.25rem;">
                                                                                <p style="font-weight: 200;font-size: 14px;">
                                                                                    {{$idea->idea_desc}}
                                                                                </p>
                                                                            </div>



                                                                        </tr>
                                                                    </table>
                                                                </div>
                                                            @else
                                                                <div class="collapse" id="collapseExample"
                                                                     style="text-align: center; box-sizing: border-box;display: block;">
                                                                    <table style="width: 100%;">
                                                                        <tr>
                                                                            <div class="card card-body"
                                                                                 style="text-align: left; display: inline-block;min-height: 8.5em;box-sizing: border-box;position: relative;-ms-flex-direction: column;flex-direction: column;min-width: 100%;word-wrap: break-word;background-color: #fff;background-clip: border-box;border: 1px solid rgba(0,0,0,.125);border-radius: .25rem;-ms-flex: 1 1 auto;flex: 1 1 auto;padding: 1.25rem;">
                                                                                <p style="font-weight: 200;font-size: 14px;">
                                                                                    {{Lang::get('messages.emptyIdeaDesc')}}
                                                                                </p>

                                                                            </div>
                                                                        </tr>
                                                                    </table>
                                                                </div>
                                                            @endif

                                                               <button class="nomob">

                                                                   View idea in Desktop!

                                                               </button>

                                                        </div>
                                                        @endif
                                                    @endforeach
                                                </div>
                                            @endisset
                                            @isset($process->processIssues)
                                                <div name="new-issues">

                                                    <div
                                                        style="text-align: center; display:flex;width:100%;padding-top:2em;padding-bottom:1em">
                                                        <table style="width:100%;table-layout: fixed;">
                                                            <tr>
                                                                <td style="width:36%;text-align: left;">
                                                        <span
                                                            style="font-size: 18px;font-weight: 700; min-width: max-content;">
                                                            {{$process->process_title}}
                                                            Issues</span>
                                                                </td>
                                                                <td text-align: center;">
                                                                    <hr
                                                                        style="width:100%;margin-right:0.5em;margin-left:0.5em;height: 1px;margin-top: 13px;color: black;">
                                                                </td>
                                                                <td style="width:24%;text-align: right;">
                                                        <span
                                                            style="padding-right: 2px;font-size: 14px !important;min-width: max-content;font-weight: 300;padding-top: 3px;">(
                                                            {{count($process->processIssues)}}
                                                            {{Lang::get('messages.Issues')}}
                                                            )</span>
                                                                </td>
                                                            </tr>
                                                        </table>
                                                    </div>

                                                    @foreach($process->processIssues as $issue)
                                                        <div style="box-sizing: border-box; padding-bottom:10px">
                                                            <div class="row main-header"
                                                                 style="text-align: center; margin: 0em;background-color: #263138 !important;box-sizing: border-box;display: flex;-ms-flex-wrap: wrap;flex-wrap: wrap;color: #fff;border-radius: 5px;">
                                                                <table
                                                                    style="width:100%;table-layout: fixed;background-color:#263138;border-radius: 5px;padding: 0px 10px;">
                                                                    <tr>
                                                                        <td style="width: 10%;text-align:center">
                                                                            <div class="col-md-1"
                                                                                 style="box-sizing: border-box;position: relative;min-height: 1px;-ms-flex: 0 0 8.333333%;flex: 0 0 8.333333%;">
                                                                                <div class="navbar-brand"
                                                                                     style="color: #fff;box-sizing: border-box;text-decoration: underline;background-color: transparent;-webkit-text-decoration-skip: objects;display: inline-block;font-size: 1.25rem;line-height: inherit;white-space: nowrap;height:100%;"
                                                                                     href="#">
                                                                                    <img src="https://i.imgur.com/mRZVc8m.png"
                                                                                         width="50" height="50" class="d-inline-block"
                                                                                         alt=""
                                                                                         style="padding: 8px 8px 0px 8px;box-sizing:content-box;border-style: none;page-break-inside: avoid;display: inline-block!important;">
                                                                                </div>
                                                                            </div>
                                                                        </td>
                                                                        <td style="text-align: left;">
                                                                            <?php $date = date_parse($issue->created_at); ?>
                                                                            <div class="col-md-10"
                                                                                 style="box-sizing: border-box;position: relative;padding-top:0.5825rem; padding-bottom:0.3125rem; width: 100%;min-height: 1px;padding-right: 15px;padding-left: 0.5235rem;-ms-flex: 0 0 83.333333%;flex: 0 0 83.333333%;">
                                                                                <table style="color: #fff;">
                                                                                    <tr>
                                                                                        <p class="mb-0"
                                                                                           style="font-size: x-small;box-sizing: border-box;margin-top: 0;margin-bottom: 0!important;orphans: 3;widows: 3;line-height:1.25;">
                                                                                            {{$date['hour'] . ':' . $date['minute'] . '-' . $date['day'] .'/'.$date['month'] .'/'. $date['year']}}
                                                                                        </p>
                                                                                    </tr>
                                                                                    <tr>
                                                                                        <strong
                                                                                            style="box-sizing: border-box;font-weight: bolder;line-height:1.25;">
                                                                                            {{$issue->issue_path[0]}}
                                                                                        </strong>
                                                                                    </tr>
                                                                                    <tr>

                                                                                        <p class="mb-0"
                                                                                           style=" text-align: left; font-size: x-small;box-sizing: border-box;margin-top: 0;margin-bottom: 0!important;orphans: 3;widows: 3;line-height: 1.25;">
                                                                                            @if(isset($issue->effect))
                                                                                             {{$issue->effect}}
                                                                                            @else
                                                                                              {{Lang::get('messages.noEffect')}}
                                                                                            @endif
                                                                                        </p>

                                                                                    </tr>

                                                                                </table>
                                                                            </div>
                                                                        </td>
                                                                    </tr>
                                                                </table>
                                                            </div>
                                                            @if ($issue->issue_desc)
                                                                <div class="collapse" id="collapseExample"
                                                                     style="text-align: center; box-sizing: border-box;display: block;">
                                                                    <table style="width: 100%;">
                                                                        <tr>
                                                                            <div class="card card-body"
                                                                                 style="text-align: left; display: inline-block;min-height: 8.5em;box-sizing: border-box;position: relative;-ms-flex-direction: column;flex-direction: column;min-width: 100%;word-wrap: break-word;background-color: #fff;background-clip: border-box;border: 1px solid rgba(0,0,0,.125);border-radius: .25rem;-ms-flex: 1 1 auto;flex: 1 1 auto;padding: 1.25rem;">
                                                                                <p style="font-weight: 200;font-size: 14px;">
                                                                                    {{$issue->issue_desc}}

                                                                                </p>

                                                                            </div>
                                                                        </tr>
                                                                    </table>
                                                                </div>
                                                            @else
                                                                <div class="collapse" id="collapseExample"
                                                                     style="text-align: center; box-sizing: border-box;display: block;">
                                                                    <table style="width: 100%;">
                                                                        <tr>
                                                                            <div class="card card-body"
                                                                                 style="text-align: left; display: inline-block;min-height: 8.5em;box-sizing: border-box;position: relative;-ms-flex-direction: column;flex-direction: column;min-width: 100%;word-wrap: break-word;background-color: #fff;background-clip: border-box;border: 1px solid rgba(0,0,0,.125);border-radius: .25rem;-ms-flex: 1 1 auto;flex: 1 1 auto;padding: 1.25rem;">
                                                                                <p style="font-weight: 200;font-size: 14px;">
                                                                                    {{Lang::get('messages.emptyIdeaDesc')}}

                                                                                </p>

                                                                            </div>
                                                                        </tr>
                                                                    </table>
                                                                </div>
                                                            @endif
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @endisset
                                            @isset($process->processIdeaIssues)

                                                <div name="new-comments">

                                                    <div
                                                        style="text-align: center; display:flex;width:100%;padding-top:2em;padding-bottom:1em">
                                                        <table style="width:100%;table-layout: fixed;">
                                                            <tr>
                                                                <td style="width:40%;text-align: left;">
                                                        <span
                                                            style="font-size: 18px;font-weight: 700; min-width: max-content;">
                                                            {{$process->process_title}}
                                                            Comments</span>
                                                                </td>
                                                                <td style="width:40%;text-align: center;">
                                                                    <hr
                                                                        style="width:100%;margin-right:0.5em;margin-left:0.5em;height: 1px;margin-top: 13px;color: black;">
                                                                </td>
                                                                <td style="width:34%;text-align: right;">
                                                        <span
                                                            style="padding-right: 2px;font-size: 14px !important;min-width: max-content;font-weight: 300;padding-top: 3px;">(
                                                            {{count($process->processIdeaIssues)}}

                                                            {{Lang::get('messages.Comments')}}
                                                            )</span>
                                                                </td>
                                                            </tr>
                                                        </table>
                                                    </div>
                                                    @foreach($process->processIdeaIssues as $ideaIssue)
                                                        <div style="box-sizing: border-box; padding-bottom:10px">
                                                            <div class="row main-header"
                                                                 style="text-align: center; margin: 0em;background-color: #263138 !important;box-sizing: border-box;display: flex;-ms-flex-wrap: wrap;flex-wrap: wrap;color: #fff;border-radius: 5px;">
                                                                <table
                                                                    style="width:100%;table-layout: fixed;background-color:#263138;border-radius: 5px;padding: 0px 10px;">
                                                                    <tr>
                                                                        <td style="width: 10%;text-align: center;">
                                                                            <div class="col-md-1"
                                                                                 style="box-sizing: border-box;position: relative;min-height: 1px;-ms-flex: 0 0 8.333333%;flex: 0 0 8.333333%;">
                                                                                <div class="navbar-brand"
                                                                                     style="color: #fff;box-sizing: border-box;text-decoration: underline;background-color: transparent;-webkit-text-decoration-skip: objects;display: inline-block;font-size: 1.25rem;line-height: inherit;white-space: nowrap;height:100%;"
                                                                                     href="#">
                                                                                    @if ($ideaIssue->ideaIssue_type === 'IMPROVEMENT')
                                                                                        <img src="https://i.imgur.com/oxicZFM.png"
                                                                                             width="50" height="50" class="d-inline-block"
                                                                                             alt=""
                                                                                             style="padding: 8px 8px 0px 8px;box-sizing:content-box;border-style: none;page-break-inside: avoid;display: inline-block!important;">
                                                                                    @else
                                                                                        <img src="https://i.imgur.com/VNoUCeK.png" width="50" height="50" class="d-inline-block" alt="" style="padding: 8px 8px 0px 8px;box-sizing:content-box;border-style: none;page-break-inside: avoid;display: inline-block!important;">
                                                                                    @endif
                                                                                </div>
                                                                            </div>
                                                                        </td>
                                                                        <td style="text-align: left;">
                                                                            <?php $date = date_parse($ideaIssue->created_at); ?>
                                                                            <div class="col-md-10"
                                                                                 style="box-sizing: border-box;position: relative;padding-top:0.5825rem; padding-bottom:0.3125rem; width: 100%;min-height: 1px;padding-right: 15px;padding-left: 0.5735rem;-ms-flex: 0 0 83.333333%;flex: 0 0 83.333333%;">

                                                                                <table style="color: #fff;">
                                                                                    <tr>
                                                                                        <p class="mb-0"
                                                                                           style="font-size: x-small;box-sizing: border-box;margin-top: 0;margin-bottom: 0!important;orphans: 3;widows: 3;line-height:1.25;">
                                                                                            {{$date['hour'] . ':' . $date['minute'] . '-' . $date['day'] .'/'.$date['month'] .'/'. $date['year']}}
                                                                                        </p>
                                                                                    </tr>
                                                                                    <tr>

                                                                                        <strong
                                                                                            style="box-sizing: border-box;font-weight: bolder;line-height:1.25;">
                                                                                            {{$ideaIssue->ideaIssue_idea_title}}
                                                                                        </strong>
                                                                                    </tr>
                                                                                    @if ($ideaIssue->ideaIssue_idea_desc !== NULL)
                                                                                        <tr>
                                                                                            <p class="mb-0"
                                                                                               style=" text-align: left; font-size: x-small;box-sizing: border-box;margin-top: 0;margin-bottom: 0!important;orphans: 3;widows: 3;line-height: 1.25;">
                                                                                                {{$ideaIssue->ideaIssue_title}}
                                                                                            </p>
                                                                                        </tr>
                                                                                    @else
                                                                                        <tr>
                                                                                            <p class="mb-0"
                                                                                               style=" text-align: left; font-size: x-small;box-sizing: border-box;margin-top: 0;margin-bottom: 0!important;orphans: 3;widows: 3;line-height: 1.25;">
                                                                                                {{Lang::get('messages.emptyIdeaDesc')}}
                                                                                            </p>
                                                                                        </tr>
                                                                                    @endif
                                                                                </table>

                                                                            </div>
                                                                        </td>





                                                                    </tr>
                                                                </table>
                                                            </div>
                                                            @if ($ideaIssue->ideaIssue_desc)
                                                                <div class="collapse" id="collapseExample"
                                                                     style="text-align: center; box-sizing: border-box;display: block;">
                                                                    <table style="width: 100%;">
                                                                        <tr>
                                                                            <div class="card card-body"
                                                                                 style="text-align: left; display: inline-block;min-height: 8.5em;box-sizing: border-box;position: relative;-ms-flex-direction: column;flex-direction: column;min-width: 100%;word-wrap: break-word;background-color: #fff;background-clip: border-box;border: 1px solid rgba(0,0,0,.125);border-radius: .25rem;-ms-flex: 1 1 auto;flex: 1 1 auto;padding: 1.25rem;">
                                                                                <p style="font-weight: 200;font-size: 14px;">
                                                                                    {{$ideaIssue->ideaIssue_desc}}
                                                                                </p>

                                                                            </div>
                                                                        </tr>
                                                                    </table>
                                                                </div>
                                                            @else
                                                                <div class="collapse" id="collapseExample"
                                                                     style="text-align: center; box-sizing: border-box;display: block;">
                                                                    <table style="width: 100%;">
                                                                        <tr>
                                                                            <div class="card card-body"
                                                                                 style="text-align: left; display: inline-block;min-height: 8.5em;box-sizing: border-box;position: relative;-ms-flex-direction: column;flex-direction: column;min-width: 100%;word-wrap: break-word;background-color: #fff;background-clip: border-box;border: 1px solid rgba(0,0,0,.125);border-radius: .25rem;-ms-flex: 1 1 auto;flex: 1 1 auto;padding: 1.25rem;">

                                                                                <p style="font-weight: 200;font-size: 14px;">
                                                                                {{Lang::get('messages.emptyIdeaDesc')}}
                                                                                </p>

                                                                            </div>
                                                                        </tr>
                                                                    </table>
                                                                </div>
                                                            @endif
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @endisset
                                        </div>
                                    </a>
                                @endforeach
                            @endforeach
                        @endisset
                        <br>
                        <div style="text-align: center; box-sizing: border-box;display: block;">
                            <table style="width: 100%;">
                                <tr>
                                    <footer style="box-sizing: border-box;display: block;padding-bottom: 1rem;">
                                        <p class="text-center pt-5"
                                           style="color: #939393;box-sizing: border-box;margin-top: 0;margin-bottom: 1rem;orphans: 3;widows: 3;padding-top: 3rem!important;text-align: center!important;">
                                            Don't wish to receive weekly digest? <code
                                                style="color: #4294D0;box-sizing: border-box;font-family: SFMono-Regular,Menlo,Monaco,Consolas,&quot;Liberation Mono&quot;,&quot;Courier New&quot;,monospace;font-size: 87.5%;word-break: break-word;"><u
                                                    style="box-sizing: border-box;"><a
                                                        href="https://devcore.app/profile">
                                                        Manage your
                                                        email notifications! </a></u></code></p>
                                    </footer>
                                </tr>
                            </table>
                        </div>

                    </div>

                </div>
            </div>
        </td>
        <td class="hide_on_mobile"></td>
    </tr>

</table>

<script language="javascript" style="box-sizing: border-box;">
    function changeImage(data) {
        console.log(data.src)
        if (data.src == "file:///C:/Users/Hp/Desktop/dev%20core%20email%20templates/img/down-arrow.png") {
            data.src = "file:///C:/Users/Hp/Desktop/dev%20core%20email%20templates/img/upper-arrow.png";
        } else {
            data.src = "file:///C:/Users/Hp/Desktop/dev%20core%20email%20templates/img/down-arrow.png";
        }
    }
</script>



<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous" style="box-sizing: border-box;"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous" style="box-sizing: border-box;"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous" style="box-sizing: border-box;"></script>
</body>

</html>
