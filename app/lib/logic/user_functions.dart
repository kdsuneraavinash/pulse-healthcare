import 'dart:convert';

import 'package:flutter/foundation.dart';
import 'package:http/http.dart' as http;
import 'package:meta/meta.dart';
import 'package:pulse_healthcare/logic/timeline_entry.dart';
import 'package:pulse_healthcare/logic/user.dart';
import 'package:pulse_healthcare/logic/user_manager.dart';

abstract class UserFunctions {
  @protected
  User user;
  final String website;

  UserFunctions(website) : this.website = website {
    this.user = User();
  }

  Future<String> _getDataAndProcess(
      {String apiLink, Function ifOkay, Function ifError}) async {

    ifOkay ??= _doNothing;
    ifError ??= _doNothing;

    http.Response response =
        await http.get(apiLink, headers: {'cookie': user.headers});
    Map<String, dynamic> data =
        json.decode(response.body.replaceAll(",]", "]"));

    bool success = data['ok'] == 'true';

    if (success) {
      _updateCookie(response);
      ifOkay(data);
    } else {
      ifError(data);
    }
    if (success) return null;
    return data['message'];
  }

  /// Login callback
  @protected
  Future<String> login(String username, String password) async {
    return await _getDataAndProcess(
      apiLink: "http://$PC_IP/api/login?account=$username&password=$password",
      ifError: (_) {
        user.headers = "";
      },
    );
  }

  /// Retrive user data callback
  @protected
  Future<String> getUserData() async {
    return await _getDataAndProcess(
      apiLink: "http://$PC_IP/api/profile",
      ifOkay: (data) {
        user.userId = data['data']['id'];
        user.name = data['data']['name'];
        user.nic = data['data']['nic'];
        user.email = data['data']['email'];
        user.phoneNumber = data['data']['phone_number'];
        user.address = data['data']['address'];
      },
    );
  }

  /// Retrieve timeline data callback
  @protected
  Future<String> getTimelineData() async {
    return await _getDataAndProcess(
      apiLink: "http://$PC_IP/api/timeline",
      ifOkay: (data) {
        user.timeline = TimelineEntry.getTimeline(
            List<Map<String, dynamic>>.from(data['prescriptions']));
      },
    );
  }

  /// Logout callback
  @protected
  Future<String> logout() async {
    return await _getDataAndProcess(apiLink: "http://$PC_IP/api/logout");
  }

  void _updateCookie(http.Response response) {
    String rawCookie = response.headers['set-cookie'];
    Map<String, String> cookieJar = Map();

    if (rawCookie != null) {
      for (String s in rawCookie.split(RegExp('[,;]'))) {
        if (s.contains("=")) {
          List<String> params = s.split("=");
          if (params.length != 2) continue;
          String key = params[0];
          String value = params[1];
          if (key == "session_user" || key == "session_key") {
            cookieJar[key] = value;
          }
        }
      }

      List<String> params = [];
      for (String key in cookieJar.keys) {
        String param = "$key=${cookieJar[key]}";
        params.add(param);
      }

      user.headers = params.join(";");
    }
  }

  void _doNothing(_) {}
}
