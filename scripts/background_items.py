#!/usr/local/munkireport/munkireport-python3

import subprocess
import os
import plistlib
import sys
import platform
import re

def get_background_items():

    cmd = ['/usr/bin/sfltool', 'dumpbtm']
    proc = subprocess.Popen(cmd, shell=False, bufsize=-1, stdin=subprocess.PIPE, stdout=subprocess.PIPE, stderr=subprocess.PIPE)
    (output, unused_error) = proc.communicate()

    btm_info = output.decode("utf-8", errors="ignore")
    out = []
    uid = ""
    user = ""
    last_btm_index = 0

    for btm_items in btm_info.split('UUID: '):
        result = {'user': user}
        line_index = 0
        for item in btm_items.split('\n'):
            line_index += 1
            if " Records for UID " in item:
                uid = item.replace("Records for UID", "").split(':')[0].strip()
                cmd = ['/usr/bin/id', '-un', uid]
                proc = subprocess.Popen(cmd, shell=False, bufsize=-1, stdin=subprocess.PIPE, stdout=subprocess.PIPE, stderr=subprocess.PIPE)
                (output, unused_error) = proc.communicate()

                username = output.decode("utf-8", errors="ignore").strip()
               
                if username == "":
                    username = "System"

                result['user'] = username
                user = username

            elif " #" in item and ":" in item and item.split(":")[1] == "":
                last_btm_index = int(item.replace("#", "").replace(":", "").strip())
                result['btm_index'] = uid+" - "+str((last_btm_index - 1)) # We need to subtract 1 because of the UUID split
            elif "                 Name: " in item:
                name = item.replace("Name:", "").replace("(null)", "").strip()
                if name != "":
                    result['name'] = name

            elif "       Developer Name: " in item:
                developer_name = item.replace("Developer Name:", "").replace("(null)", "").strip()
                if developer_name != "":
                    result['developer_name'] = developer_name

            elif "                  URL: " in item:
                url = item.replace("URL:", "").replace("(null)", "").replace("file://", "").strip()
                if url != "":
                    result['url'] = url

            elif "      Team Identifier: " in item:
                result['team_id'] = item.replace("Team Identifier:", "").replace("(null)", "").strip()
            elif "    Assoc. Bundle IDs: " in item:
                result['assoc_bundle_id'] = item.replace("Assoc. Bundle IDs:", "").replace("[", "").replace("]", "").strip()
            elif "                 Type: " in item:
                result['type'] = item.replace("Type:", "").replace("(null)", "").split('(')[0].strip().title()
            elif "           Identifier: " in item:
                result['identifier'] = item.replace("Identifier:", "").strip()
            elif "           Generation: " in item:
                result['generation'] = item.replace("Generation:", "").strip()
            elif "    Parent Identifier: " in item:
                result['parent_id'] = item.replace("Parent Identifier:", "").strip()
            elif "      Executable Path: " in item:
                result['executable_path'] = item.replace("Executable Path:", "").strip()

            elif "          Disposition: " in item:
                disposition = item.replace("Disposition:", "").strip()
                if "disabled" in disposition:
                    result['state_enabled'] = 0
                elif "enabled" in disposition:
                    result['state_enabled'] = 1
                if "disallowed" in disposition:
                    result['state_allowed'] = 0
                elif "allowed" in disposition:
                    result['state_allowed'] = 1
                if "not visible" in disposition:
                    result['state_visible'] = 0
                elif "visible" in disposition:
                    result['state_visible'] = 1
                if "not notified" in disposition:
                    result['state_notified'] = 0
                elif "notified" in disposition:
                    result['state_notified'] = 1
            elif "  Embedded Item Identifiers:" in item:
                embedded_item_ids = ""
                line_index_process = line_index # We don't want to break the current index
                item = btm_items.split('\n')[line_index_process]
                
                while item != "":
                    embedded_item_ids = embedded_item_ids + item.split(': ')[1].strip() + "\n"
                    item = btm_items.split('\n')[line_index_process + 1]
                    line_index_process += 1
                
                result['embedded_item_ids'] = embedded_item_ids.strip()

        # Only add if we have actual data
        if len(result) > 4:

            # # Skip if dumpped item is com.apple.PackageKit.DeferredInstallFixup
            # if 'embedded_item_ids' in result and result['embedded_item_ids'] == "com.apple.PackageKit.DeferredInstallFixup":
            #     pass
            # elif 'name' not in result and 'developer_name' not in result and 'embedded_item_ids' not in result and 'identifier' in result and result['identifier'] == "Unknown Developer":
            # Skip if empty entry
            if 'name' not in result and 'developer_name' not in result and 'embedded_item_ids' not in result and 'identifier' in result and result['identifier'] == "Unknown Developer":
                pass
            else:

                if 'btm_index' not in result:
                    result['btm_index'] = uid+" - "+str(last_btm_index)

                out.append(result)

    return out

def getDarwinVersion():
    """Returns the Darwin version."""
    # Catalina -> 10.15.7 -> 19.6.0 -> 19
    darwin_version_tuple = platform.release().split('.')
    return int(darwin_version_tuple[0]) 

def main():
    """Main"""

    # Check if macOS 11 (Darwin 20) or higher
    if getDarwinVersion() >= 20:
        result = get_background_items()
    else:
        # Background items are not supported on macOS 10.15 and older
        result = []

    # Write background items results to cache
    cachedir = '%s/cache' % os.path.dirname(os.path.realpath(__file__))
    output_plist = os.path.join(cachedir, 'background_items.plist')
    try:
        plistlib.writePlist(result, output_plist)
    except:
        with open(output_plist, 'wb') as fp:
            plistlib.dump(result, fp, fmt=plistlib.FMT_XML)

if __name__ == "__main__":
    main()
