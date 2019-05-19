import 'dart:convert';

import 'package:flutter/material.dart';
import 'package:http/http.dart' as http;

const String PC_IP = "10.0.2.2:8000";
const String EMPTY = "-";

class UserManager with ChangeNotifier {
  final String website; // website url
  User _user; // user object
  bool _pending; // is some action pending

  get authorized => _user.authorized;
  get pending => _pending;

  get userId => _user.userId ?? EMPTY;
  get name => _user.name ?? EMPTY;
  get nic => _user.nic ?? EMPTY;
  get email => _user.email ?? EMPTY;
  get phoneNumber => _user.phoneNumber ?? EMPTY;
  get address => _user.address ?? EMPTY;

  UserManager({this.website = PC_IP}) {
    _user = User();
    _pending = false;
  }

  setPending(bool v) {
    _pending = v;
    notifyListeners();
  }

  Future<String> _getDataAndProcess({
    String apiLink,
    Function ifOkay,
    Function ifError,
  }) async {
    setPending(true);

  print(_user.headers);
    http.Response response =
        await http.get(apiLink, headers: {'cookie': _user.headers});
    Map<String, dynamic> data = json.decode(response.body);

    bool success = data['ok'] == 'true';

    if (success) {
      updateCookie(response);
      ifOkay(data);
    } else {
      ifError(data);
    }
    setPending(false);
    if (success) return null;
    return data['message'];
  }

  Future<String> login(String username, String password) async {
    String err = await _getDataAndProcess(
      apiLink: "http://$PC_IP/api/login?account=$username&password=$password",
      ifOkay: (_) => null,
      ifError: (_) => _user.headers = "",
    );
    if (err == null) {
      err = await getUserData();
      if (err != null) _user.headers = "";
    }
    return err;
  }

  Future<String> getUserData() async {
    return await _getDataAndProcess(
      apiLink: "http://$PC_IP/api/profile",
      ifOkay: (data) {
        _user.userId = data['data']['id'];
        _user.name = data['data']['name'];
        _user.nic = data['data']['nic'];
        _user.email = data['data']['email'];
        _user.phoneNumber = data['data']['phone_number'];
        _user.address = data['data']['address'];
      },
      ifError: (_) => null,
    );
  }

  void updateCookie(http.Response response) {
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

      _user.headers = params.join(";");
    }
  }
}

class User {
  String headers;
  bool get authorized => headers != "";
  String userId;
  String name;
  String nic;
  String address;
  String email;
  String phoneNumber;

  User() {
    headers = "";
  }
}
