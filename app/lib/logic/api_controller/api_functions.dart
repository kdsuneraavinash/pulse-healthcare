import 'dart:convert';

import 'package:flutter/foundation.dart';
import 'package:http/http.dart' as http;
import 'package:meta/meta.dart';
import 'package:pulse_healthcare/logic/data/doctor.dart';
import 'package:pulse_healthcare/logic/data/timeline_entry.dart';
import 'package:pulse_healthcare/logic/data/user.dart';

/// Function definition for onSuccess and OnError funcions
typedef MapAcceter(Map<String, dynamic> data);

/// Abstract function to isolate unlining user functions from user manager
abstract class APIFunctions {
  static const String PC_IP = "10.0.2.2:8000";
  static const String EMPTY = "-";

  /// Current user object
  @protected
  User user;

  /// Website link (currently it is set to computer IP)
  final String website;

  APIFunctions(this.website) {
    /// Initialize User object
    this.user = User();
  }

  /// Most base function to visit a given api link and parse data.
  /// Outputs null if no error was thrown.
  /// Otherwise error message will be thhrown.
  Future<String> _getDataAndProcess({
    String apiLink,
    MapAcceter onSuccess,
    MapAcceter onError,
  }) async {
    /// If undefined, reset to do nothing
    onSuccess ??= (_) {};
    onError ??= (_) {};

    http.Response response;

    try {
      /// Try to get api response
      response = await http.get(apiLink, headers: {'cookie': user.headers});
    } catch (SocketException) {
      /// Error occurred, so return the error message
      String errorMessage = "Network Error";
      onError({'message': errorMessage});
      return errorMessage;
    }

    /// Try to decode json, (Since returned json has a trialing comma at the end, we have to remove it)
    String parsedJson = response.body.replaceAll(",]", "]");
    Map<String, dynamic> data = json.decode(parsedJson);

    /// Check if api request was successful
    bool success = (data['ok'] == 'true');

    if (success) {
      /// If successfull, update cookies(to save session_id and session_key)
      _updateCookie(response);
      onSuccess(data);
      return null;
    } else {
      onError(data);
      return data['message'];
    }
  }

  /// Login callback
  @protected
  Future<String> login(String username, String password) async {
    return await _getDataAndProcess(
      apiLink: "http://$PC_IP/api/login?account=$username&password=$password",
      onError: (_) {
        /// Clear headers if login failed
        user.headers = "";
      },
    );
  }

  /// Retrive user data callback
  @protected
  Future<String> getUserData() async {
    return await _getDataAndProcess(
      apiLink: "http://$PC_IP/api/profile",
      onSuccess: (data) {
        user.userId = data['data']['id'];
        user.name = data['data']['name'];
        user.nic = data['data']['nic'];
        user.email = data['data']['email'];
        user.phoneNumber = data['data']['phone_number'];
        user.address = data['data']['address'];
      },
    );
  }

  /// Retrive user data callback
  @protected
  Future<List<Doctor>> getSearchResults(String searchTerm) async {
    List<Doctor> maps;

    await _getDataAndProcess(
        apiLink: "http://$PC_IP/api/search?name=$searchTerm",
        onSuccess: (data) {
          maps = List<Doctor>.from(
            /// Create doctor list
            List<Map>.from(data['results']).map<Doctor>(
              /// Parse map to doctor
              (v) => Doctor.fromMap(
                    /// Parse each map
                    Map<String, String>.from(v),
                  ),
            ),
          ).toList();
        });
    return maps;
  }

  /// Retrieve timeline data callback
  @protected
  Future<String> getTimelineData() async {
    return await _getDataAndProcess(
      apiLink: "http://$PC_IP/api/timeline",
      onSuccess: (data) {
        user.timeline = TimelineEntry.getTimeline(
          List<Map<String, dynamic>>.from(data['prescriptions']),
        );
      },
    );
  }

  /// Logout callback
  @protected
  Future<String> logout() async {
    return await _getDataAndProcess(apiLink: "http://$PC_IP/api/logout");
  }

  /// Get response and update cookies with session_id and session_key
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
}
