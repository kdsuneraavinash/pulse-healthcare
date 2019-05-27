import 'package:pulse_healthcare/logic/timeline_entry.dart';

class User {
  String headers;
  bool get authorized => headers != "";
  String userId;
  String name;
  String nic;
  String address;
  String email;
  String phoneNumber;
  List<TimelineEntry> timeline;

  User() {
    headers = "";
  }
}
