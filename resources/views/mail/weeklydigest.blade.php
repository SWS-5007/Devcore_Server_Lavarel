<!DOCTYPE html>
<html lang="en" xmlns="http://www.w3.org/1999/xhtml" xmlns:o="urn:schemas-microsoft-com:office:office">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <meta name="x-apple-disable-message-reformatting">
    <title></title>
    <!--[if mso]>
    <style>
        table {border-collapse:collapse;border-spacing:0;border:none;margin:0;}
        div, td {padding:0;}
        div {margin:0 !important;}
    </style>
    <noscript>
        <xml>
            <o:OfficeDocumentSettings>
                <o:PixelsPerInch>96</o:PixelsPerInch>
            </o:OfficeDocumentSettings>
        </xml>
    </noscript>
    <![endif]-->
    <style>
        table, td, div, h1, p {
            font-family: Arial, sans-serif;
            border-spacing: 0 !important;
        }
        @media screen and (max-width: 530px) {
            .unsub {
                display: block;
                padding: 8px;
                margin-top: 14px;
                border-radius: 5px;
                background-color: #fff;
                text-decoration: none !important;
                font-weight: bold;
            }
            .col-lge {
                max-width: 100% !important;
            }

        }

        @media screen and (max-device-width: 530px) {
            .mobilecontent {
                display: inline-block !important;
                max-height: none !important;
            }

            .mobilemargin {
                margin-right:10px !important;
            }
        }

        @media screen and (min-device-width: 530px) {
            .desktopcontent {
                display: inline-block !important;
                max-height: none !important;
            }

            .desktopmargin {
                margin-right:95px !important;
            }
        }

        @media screen and (min-width: 531px) {
            .col-sml {
                max-width: 15% !important;
            }
            .col-lge {
                max-width: 85% !important;
            }
        }

        @media screen and (min-width: 800px) {
            .col-lge p a {
                display:inline-block!important;
            }
        }
        img {
            display: block;
            margin: auto
        }
    </style>
</head>
<body style="margin:0;padding:0;word-spacing:normal;background-color:#f8f8f8;">
<div role="article" aria-roledescription="email" lang="en" style="text-size-adjust:100%;-webkit-text-size-adjust:100%;-ms-text-size-adjust:100%;background-color:#939297;">
    <table role="presentation" style="width:100%;border:none;border-spacing:0;">
        <tr>
            <td align="center" style="padding:0;">
                <!--[if mso]>
                <table role="presentation" align="center" style="width:600px;">
                    <tr>
                        <td>
                <![endif]-->
                <table role="presentation" style="width:94%;max-width:600px;border:none;border-spacing:0;text-align:left;font-family:Arial,sans-serif;font-size:16px;line-height:22px;color:#363636;">
                    <tr>
                        <td style="font-family: -apple-system,BlinkMacSystemFont,&quot;Segoe UI&quot;,Roboto,&quot;Helvetica Neue&quot;,Arial,sans-serif,&quot;Apple Color Emoji&quot;,&quot;Segoe UI Emoji&quot;,&quot;Segoe UI Symbol&quot;,&quot;Noto Color Emoji&quot;;padding:20px;background-color:#000;color:#f8f8f8;box-sizing: border-box;position: relative;min-width: 100%;min-height: 1px;">

                            {{Lang::get('messages.hello')}}

                            <strong style="box-sizing: border-box;font-weight: bolder;color:#fff">

                                {{Lang::get('messages.name', ['name' => $user->first_name])}}

                            </strong><br style="box-sizing: border-box;">
                            <strong style="box-sizing: border-box;font-weight: bolder;">

                                {{Lang::get('messages.weeklyDigest')}}

                            </strong>
                            {{Lang::get('messages.summaryFromLastWeek')}}

                        </td>
                        <td></td>


                    @isset($data)
                        @foreach($data as $processes)
                    <tr>


                        @foreach ($processes as $key => $process)

                        <!-- IDEAS -->
                        @if(isset($process->processIdeas))
                                <td style="padding:20px;background-color:#000;color:#fff;text-align: left;border-top:15px solid #000">

                                <h1 style="margin-top:0;margin-bottom:16px;font-size:26px;line-height:32px;font-weight:bold;letter-spacing:-0.02em;">
                                <span
                                    style="font-size: 18px;font-weight: 700; min-width: max-content;">{{$process->process_title}} Ideas</span>
                                <span
                                    style="color:#fff;padding-right: 2px;font-size: 14px !important;min-width: max-content;font-weight: 300;padding-top: 3px;">
                                    ({{count($process->processIdeas)}}
                                    {{Lang::get('messages.Ideas')}})
                                </span>
                            </h1>
                        </td>
                        </tr>

                            @foreach($process->processIdeas as $idea)
                                @if(isset($idea->idea_path[0]))

                            <tr>
                                <td style="word-break:break-all;padding: 30px 10px 10px 20px;font-size:0;background-color:#ffffff;border-bottom:1px solid #f0f0f5;border-color:rgba(201,201,207,.35);">
                                    <?php
                                    $ideaTitle = $idea->idea_title;
                                    $ideaDesc = $idea->idea_desc ? $idea->idea_desc : Lang::get('messages.emptyIdeaDesc');
                                    $date = date_parse($idea->idea_created_at);
                                    $ideaUrl = $idea->idea_url ? $idea->idea_url : url(config('app.url'));
                                    ?>
                                    <!--[if mso]>
                                    <table role="presentation" width="100%">
                                        <tr>
                                            <td style="width:145px;" align="left" valign="top">
                                    <![endif]-->
                                    <div class="col-sml" style="display:inline-block;width:100%;max-width:145px;vertical-align:top;text-align:left;font-family:Arial,sans-serif;font-size:14px;color:#363636;">
                                        <img src="https://imgur.com/B3VTDRf.png" width="50" alt="" style="width:50px;max-width:80%;margin-bottom:20px;">
                                    </div>
                                    <!--[if mso]>
                                    </td>
                                    <td style="width:395px;padding-bottom:20px;word-break:break-all;" valign="top">
                                    <![endif]-->

                                    <div class="col-lge desktopcontent" style="display:inline-block;width:100%;max-width:395px;vertical-align:top;padding-bottom:20px;font-family:Arial,sans-serif;font-size:16px;line-height:22px;color:#363636;">
                                        <p style="font-size:10px;margin-top:0;margin-bottom:6px;">{{$date['hour'] . ':' . $date['minute'] . '-' . $date['day'] .'/'.$date['month'] .'/'. $date['year']}}</p>
                                        <p style="font-size:16px;margin-top:0;margin-bottom:6px;font-weight:800">{{$ideaTitle}}</p>
                                        <p style="font-size:14px;margin-top:0;margin-bottom:6px;">{{$idea->idea_path[0]}}</p>
                                        <p class="mobilemargin desktopmargin" style="font-size:12px;margin-top:0;margin-bottom:12px;margin-right:80px">{{$ideaDesc}}</p>
                                        <a href="{{$ideaUrl}}" class="desktopcontent" style="display: none;text-align:right;font-family: Helvetica, sans-serif;font-size: 14px;margin: 0;padding: 0 24px;color: #ffffff;margin-top: 0;font-weight: bold;line-height: 40px;letter-spacing: 0.1ch;text-transform: uppercase;background-color: #4294d0;border-radius: 5px;box-shadow: 0 8px 14px 2px #f45c2324, 0 6px 20px 5px #f45c231f, 0 8px 10px -5px #f45c2333;text-align: center;text-decoration: none;white-space: nowrap;-webkit-text-size-adjust: none">{{Lang::get('messages.viewInDesktop')}}</a>


                                    <!--[if mso]>
                                        <div style="font-family: Helvetica, sans-serif;font-size: 100%;margin: 0;margin-right:80px;padding: 0;margin-top: 0;text-align:right">

                                            <v:roundrect href="{{$ideaUrl}}" style="width:173px;height:40px;v-text-anchor:middle;" arcsize="50%" stroke="f" fillcolor="#4294d0" xmlns:v="urn:schemas-microsoft-com:vml" xmlns:w="urn:schemas-microsoft-com:office:word">
                                                <w:anchorlock/>
                                                <v:textbox inset="0,0,0,0">
                                                    <center>

                                            <a href="{{$ideaUrl}}" style="font-family: Helvetica, sans-serif;font-size: 14px;margin: 0;padding: 0 24px;color: #ffffff;margin-top: 0;font-weight: bold;line-height: 40px;letter-spacing: 0.1ch;text-transform: uppercase;background-color: #4294d0;border-radius: 5px;box-shadow: 0 8px 14px 2px #f45c2324, 0 6px 20px 5px #f45c231f, 0 8px 10px -5px #f45c2333;display: inline-block;text-align: center;text-decoration: none;white-space: nowrap;-webkit-text-size-adjust: none">{{Lang::get('messages.viewInDesktop')}}</a>

                                            </center>
                                            </v:textbox>
                                            </v:roundrect>

                                        </div>
                                        <![endif]-->
                                    </div>

                                    <!--[if mso]>
                                    </td>
                                    </tr>
                                    </table>
                                    <![endif]-->
                                </td>
                            </tr>
                                @endif
                            @endforeach
                        @endif

                        <!-- ISSUES -->
                        @if(isset($process->processIssues))
                                <td style="padding:20px;background-color:#ccc;color:#fff;text-align: left;border-top:15px solid #ccc">

                            <h1 style="margin-top:0;margin-bottom:16px;font-size:26px;line-height:32px;font-weight:bold;letter-spacing:-0.02em;">
                            <span
                                style="font-size: 18px;font-weight: 700; min-width: max-content;">{{$process->process_title}} Issues</span>
                                <span
                                    style="color:#000;padding-right: 2px;font-size: 14px !important;min-width: max-content;font-weight: 300;padding-top: 3px;color:#000">
                                ({{count($process->processIssues)}}
                                    {{Lang::get('messages.Issues')}})
                            </span>
                            </h1>
                        </td>
                        </tr>

                        @foreach($process->processIssues as $issue)
                            @if(isset($issue->issue_path[0]))

                                <tr>
                                    <td style="word-break:break-all;padding: 30px 10px 10px 20px;font-size:0;background-color:#ffffff;border-bottom:1px solid #f0f0f5;border-color:rgba(201,201,207,.35);">
                                <?php
                                        $issueTitle = $issue->effect ? $issue->effect : Lang::get('messages.noEffect');
                                        $issueDesc = $issue->issue_desc ? $issue->issue_desc : Lang::get('messages.emptyIdeaDesc');
                                        $issueUrl = $issue->issue_url;
                                        $date = date_parse($issue->created_at);
                                ?>
                                <!--[if mso]>
                                        <table role="presentation" width="100%">
                                            <tr>
                                                <td style="width:145px;" align="left" valign="top">
                                        <![endif]-->
                                        <div class="col-sml" style="display:inline-block;width:100%;max-width:145px;vertical-align:top;text-align:left;font-family:Arial,sans-serif;font-size:14px;color:#363636;">
                                            <img src="https://imgur.com/SawZTBG.png" width="50" alt="" style="width:50px;max-width:80%;margin-bottom:20px;">
                                        </div>
                                        <!--[if mso]>
                                        </td>

                                        <td style="width:395px;padding-bottom:6px;word-break:break-all;" valign="top">
                                        <![endif]-->
                                        <div class="col-lge desktopcontent" style="display:inline-block;width:100%;max-width:395px;vertical-align:top;padding-bottom:20px;font-family:Arial,sans-serif;font-size:16px;line-height:22px;color:#363636;">
                                            <p style="font-size:10px;margin-top:0;margin-bottom:6px;">{{$date['hour'] . ':' . $date['minute'] . '-' . $date['day'] .'/'.$date['month'] .'/'. $date['year']}}</p>
                                            <p style="color:#000;font-size:16px;margin-top:0;margin-bottom:6px;font-weight:800">{{$issueTitle}}</p>
                                            <p style="font-size:16px;margin-top:0;margin-bottom:6px;">{{$issue->issue_path[0]}}</p>
                                            <p style="font-size:12px;margin-top:14px;margin-bottom:14px;margin-right:80px"  class="mobilemargin desktopmargin">{{$issueDesc}}</p>
                                            <a href="{{$issueUrl}}" class="desktopcontent" style="display: none;text-align:right;font-family: Helvetica, sans-serif;font-size: 14px;margin: 0;padding: 0 24px;color: #ffffff;margin-top: 0;font-weight: bold;line-height: 40px;letter-spacing: 0.1ch;text-transform: uppercase;background-color: #4294d0;border-radius: 5px;box-shadow: 0 8px 14px 2px #f45c2324, 0 6px 20px 5px #f45c231f, 0 8px 10px -5px #f45c2333;text-align: center;text-decoration: none;white-space: nowrap;-webkit-text-size-adjust: none">{{Lang::get('messages.viewInDesktop')}}</a>


                                        <!--[if mso]>
                                    <div style="font-family: Helvetica, sans-serif;font-size: 100%;margin: 0;margin-right:80px;padding: 0;margin-top: 0;text-align:right">

                                        <v:roundrect href="{{$issueUrl}}" style="width:173px;height:40px;v-text-anchor:middle;" arcsize="50%" stroke="f" fillcolor="#4294d0" xmlns:v="urn:schemas-microsoft-com:vml" xmlns:w="urn:schemas-microsoft-com:office:word">
                                            <w:anchorlock/>
                                            <v:textbox inset="0,0,0,0">
                                                <center>

                                        <a href="{{$issueUrl}}" style="font-family: Helvetica, sans-serif;font-size: 14px;margin: 0;padding: 0 24px;color: #ffffff;margin-top: 0;font-weight: bold;line-height: 40px;letter-spacing: 0.1ch;text-transform: uppercase;background-color: #4294d0;border-radius: 5px;box-shadow: 0 8px 14px 2px #f45c2324, 0 6px 20px 5px #f45c231f, 0 8px 10px -5px #f45c2333;display: inline-block;text-align: center;text-decoration: none;white-space: nowrap;-webkit-text-size-adjust: none">{{Lang::get('messages.viewInDesktop')}}</a>

                                        </center>
                                        </v:textbox>
                                        </v:roundrect>

                                    </div>
                                    <![endif]-->
                                        </div>
                                    <![endif]-->

                                        </div>
                                        <!--[if mso]>
                                        </td>
                                        </tr>
                                        </table>
                                        <![endif]-->
                                    </td>
                                </tr>
                            @endif
                        @endforeach
                        @endif

                        <!-- IDEA ISSUES -->
                        @if(isset($process->processIdeaIssues))
                            <td style="padding:20px;background-color:#ccc;color:#fff;text-align: left;border-top:15px solid #ccc">
                                <h1 style="margin-top:0;margin-bottom:16px;font-size:26px;line-height:32px;font-weight:bold;letter-spacing:-0.02em;">
                                    <span style="font-size: 18px;font-weight: 700; min-width: max-content;">{{$process->process_title}} Comments</span>
                                    <span style="color:#000;padding-right: 2px;font-size: 14px !important;min-width: max-content;font-weight: 300;padding-top: 3px;">({{count($process->processIdeaIssues)}}
                                        {{Lang::get('messages.Comments')}})
                                    </span>
                                </h1>
                            </td>
                            </tr>

                            @foreach($process->processIdeaIssues as $ideaIssue)
                                @if(isset($ideaIssue->ideaIssue_title))

                                    <tr>
                                        <td style="word-break:break-all;padding: 30px 10px 10px 20px;font-size:0;background-color:#ffffff;border-bottom:1px solid #f0f0f5;border-color:rgba(201,201,207,.35);">

                                            <?php
                                            $date = date_parse($ideaIssue->created_at);
                                            $ideaIssueTitle = $ideaIssue->ideaIssue_title;
                                            $ideaIssueDesc = $ideaIssue->ideaIssue_desc ? $ideaIssue->ideaIssue_desc : Lang::get('messages.emptyIdeaDesc');
                                            $ideaIssueUrl = $ideaIssue->ideaIssue_url;
                                            ?>
                                            <!--[if mso]>
                                            <table role="presentation" width="100%">
                                                <tr>
                                                    <td style="width:145px;" align="left" valign="top">
                                            <![endif]-->
                                            <div class="col-sml" style="display:inline-block;width:100%;max-width:145px;vertical-align:top;text-align:left;font-family:Arial,sans-serif;font-size:14px;color:#363636;">
                                                <img src="https://imgur.com/EFkglAh.png" width="50" alt="" style="width:50px;max-width:80%;margin-bottom:20px;">
                                            </div>
                                            <!--[if mso]>
                                            </td>
                                            <td style="width:395px;padding-bottom:6px;word-break:break-all;" valign="top">
                                            <![endif]-->


                                            <div class="col-lge desktopcontent" style="display:inline-block;width:100%;max-width:395px;vertical-align:top;padding-bottom:20px;font-family:Arial,sans-serif;font-size:16px;line-height:22px;color:#363636;">
                                                <p style="font-size:10px;margin-top:0;margin-bottom:6px;">{{$date['hour'] . ':' . $date['minute'] . '-' . $date['day'] .'/'.$date['month'] .'/'. $date['year']}}</p>
                                                <p style="font-size:16px;margin-top:0;margin-bottom:6px;font-weight:800">{{$ideaIssueTitle}}</p>
                                                <p style="font-size:12px;margin-top:14px;margin-bottom:14px;margin-right:80px" class="mobilemargin desktopmargin">{{$ideaIssueDesc}}</p>
                                                <a href="{{$ideaIssueUrl}}" class="desktopcontent" style="display: none;text-align:right;font-family: Helvetica, sans-serif;font-size: 14px;margin: 0;padding: 0 24px;color: #ffffff;margin-top: 0;font-weight: bold;line-height: 40px;letter-spacing: 0.1ch;text-transform: uppercase;background-color: #4294d0;border-radius: 5px;box-shadow: 0 8px 14px 2px #f45c2324, 0 6px 20px 5px #f45c231f, 0 8px 10px -5px #f45c2333;text-align: center;text-decoration: none;white-space: nowrap;-webkit-text-size-adjust: none">{{Lang::get('messages.viewInDesktop')}}</a>

                                            <!--[if mso]>
                                            <div style="font-family: Helvetica, sans-serif;font-size: 100%;margin: 0;margin-right:80px;padding: 0;margin-top: 0;text-align:right">

                                                <v:roundrect href="{{$ideaIssueUrl}}" style="width:173px;height:40px;v-text-anchor:middle;" arcsize="50%" stroke="f" fillcolor="#4294d0" xmlns:v="urn:schemas-microsoft-com:vml" xmlns:w="urn:schemas-microsoft-com:office:word">
                                                    <w:anchorlock/>
                                                    <v:textbox inset="0,0,0,0">
                                                        <center>

                                                <a href="{{$ideaIssueUrl}}" style="font-family: Helvetica, sans-serif;font-size: 14px;margin: 0;padding: 0 24px;color: #ffffff;margin-top: 0;font-weight: bold;line-height: 40px;letter-spacing: 0.1ch;text-transform: uppercase;background-color: #4294d0;border-radius: 5px;box-shadow: 0 8px 14px 2px #f45c2324, 0 6px 20px 5px #f45c231f, 0 8px 10px -5px #f45c2333;display: inline-block;text-align: center;text-decoration: none;white-space: nowrap;-webkit-text-size-adjust: none">{{Lang::get('messages.viewInDesktop')}}</a>

                                                </center>
                                                </v:textbox>
                                                </v:roundrect>

                                            </div>
                                                                <![endif]-->
                                            </div>
                                            <![endif]-->

                                            </div>
                                            <!--[if mso]>
                                            </td>
                                            </tr>
                                            </table>
                                            <![endif]-->
                                        </td>
                                    </tr>
                                @endif
                            @endforeach
                        @endif

                        @endforeach
                        @endforeach
                    @endisset
                    <tr>
                        <td style="padding:30px;text-align:center;font-size:12px;background-color:#404040;color:#cccccc;">
                            <p style="margin:0;font-size:14px;line-height:20px;">&reg;
                                Devcore<br><a class="unsub" href="https://devcore.app/profile" style="color:#cccccc;text-decoration:underline;">Manage your email notifications!</a></p>
                        </td>
                    </tr>
                </table>
                <!--[if mso]>
                </td>
                </tr>
                </table>
                <![endif]-->
            </td>
        </tr>
    </table>
</div>
</body>
</html>
