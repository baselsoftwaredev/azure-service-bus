# Azure Service Bus for PHP

This project provides a library that make it easy to access Microsoft service bus (queues and topics).

Most code has been lifted from the now archived azure for php sdk library.

Old previous non-relevant code has been removed

The old library was build on PHP 5.6, therefore it has been upgraded to support at least the latest LTS PHP 7.4.

# Features

* Queues: create, list and delete queues; send, receive, unlock and delete messages
* Topics: create, list, and delete topics; create, list, and delete subscriptions; send, receive, unlock and delete messages; create, list, and delete rules

# Getting Started
## Download Source Code

To get the source code from GitHub, type

```
git clone https://github.com/baselsoftwaredev/azure-service-bus.git
cd ./azure-service-bus
```

## Install via Composer

Planning to add composer package registry.

# Usage

## Getting Started

There are four basic steps that have to be performed before you can make a call to any Microsoft Azure API when using the libraries.

* First, include the autoloader script:

  ```PHP
  require_once "vendor/autoload.php";
  ```

* Include the namespaces you are going to use.

  To create any Microsoft Azure service client you need to use the **ServicesBuilder** class:

  ```PHP
  use WindowsAzure\Common\ServicesBuilder;
  ```

  To process exceptions you need:

  ```PHP
  use WindowsAzure\Common\ServiceException;
  ```

* To instantiate the service client you will also need a valid connection string. For accessing the service bus the format is:

    ```
    Endpoint=[yourEndpoint];SharedSecretIssuer=[yourWrapAuthenticationName];SharedSecretValue=[yourWrapPassword]
    ```

    Where the Endpoint is typically of the format `https://[yourNamespace].servicebus.windows.net`.


* Instantiate a for Service Bus "REST Proxy" - a wrapper around the available calls for the given service.

  ```PHP
  $serviceBusRestProxy = ServicesBuilder::getInstance()->createServiceBusService($connectionString);
  ```

### Create a queue

A **QueueRestProxy** object lets you create a queue with the **createQueue** method. When creating a queue, you can set options on the queue, but doing so is not required.

```PHP
$createQueueOptions = new CreateQueueOptions();
$createQueueOptions->addMetaData("key1", "value1");
$createQueueOptions->addMetaData("key2", "value2");

try {
  // Create queue.
  $queueRestProxy->createQueue("myqueue", $createQueueOptions);
} catch(ServiceException $e){
  $code = $e->getCode();
  $error_message = $e->getMessage();
  echo $code.": ".$error_message."<br />";
}
```

[Error Codes and Messages for Queues](http://msdn.microsoft.com/en-us/library/windowsazure/dd179446.aspx)


### Add a message to a queue

To add a message to a queue, use **QueueRestProxy->createMessage**. The method takes the queue name, the message text, and message options (which are optional).
For compatibility with others you may need to base64 encode message.

```PHP
try {
  // Create message.
  $msg = "Hello World!";
  // optional: $msg = base64_encode($msg);
  $queueRestProxy->createMessage("myqueue", $msg);
} catch(ServiceException $e){
  $code = $e->getCode();
  $error_message = $e->getMessage();
  echo $code.": ".$error_message."<br />";
}
```

### Peek at the next message

You can peek at a message (or messages) at the front of a queue without removing it from the queue by calling **QueueRestProxy->peekMessages**.

```PHP
// OPTIONAL: Set peek message options.
$message_options = new PeekMessagesOptions();
$message_options->setNumberOfMessages(1); // Default value is 1.

try {
  $peekMessagesResult = $queueRestProxy->peekMessages("myqueue", $message_options);
} catch(ServiceException $e){
  $code = $e->getCode();
  $error_message = $e->getMessage();
  echo $code.": ".$error_message."<br />";
}

$messages = $peekMessagesResult->getQueueMessages();

// View messages.
$messageCount = count($messages);
if($messageCount <= 0){
  echo "There are no messages.<br />";
}
else{
  foreach($messages as $message)  {
    echo "Peeked message:<br />";
    echo "Message Id: ".$message->getMessageId()."<br />";
    echo "Date: ".date_format($message->getInsertionDate(), 'Y-m-d')."<br />";
    echo "Message text: ".$message->getMessageText()."<br /><br />";
  }
}
```

### De-queue the next message

Your code removes a message from a queue in two steps. First, you call **QueueRestProxy->listMessages**, which makes the message invisible to any other code reading from the queue. By default, this message will stay invisible for 30 seconds (if the message is not deleted in this time period, it will become visible on the queue again). To finish removing the message from the queue, you must call **QueueRestProxy->deleteMessage**.

```PHP
// Get message.
$listMessagesResult = $queueRestProxy->listMessages("myqueue");
$messages = $listMessagesResult->getQueueMessages();
$message = $messages[0];

// Process message

// Get message Id and pop receipt.
$messageId = $message->getMessageId();
$popReceipt = $message->getPopReceipt();

try {
  // Delete message.
  $queueRestProxy->deleteMessage("myqueue", $messageId, $popReceipt);
} catch(ServiceException $e){
  $code = $e->getCode();
  $error_message = $e->getMessage();
  echo $code.": ".$error_message."<br />";
}
```

## Service Bus Queues
The current PHP Service Bus APIs only support ACS connection strings. You need to use PowerShell to create a new ACS Service Bus namespace at the present time.
First, make sure you have Azure PowerShell installed, then in a PowerShell command prompt, run
```PowerShell
Add-AzureAccount # this will sign you in
New-AzureSBNamespace -CreateACSNamespace $true -Name 'mytestbusname' -Location 'West US' -NamespaceType 'Messaging'
```
If it is sucessful, you will get the connection string in the PowerShell output. If you get connection errors with it and the conection string looks like Endpoint=sb://..., change it to **Endpoint=https://...**

### Create a Queue

```PHP
try {
  $queueInfo = new QueueInfo("myqueue");

  // Create queue.
  $serviceBusRestProxy->createQueue($queueInfo);
} catch(ServiceException $e){
  $code = $e->getCode();
  $error_message = $e->getMessage();
  echo $code.": ".$error_message."<br />";
}
```

[Error Codes and Messages](http://msdn.microsoft.com/en-us/library/windowsazure/dd179357)

### Send a Message

To send a message to a Service Bus queue, your application will call the **ServiceBusRestProxy->sendQueueMessage** method. Messages sent to (and received from ) Service Bus queues are instances
of the **BrokeredMessage** class.

```PHP
try {
  // Create message.
  $message = new BrokeredMessage();
  $message->setBody("my message");

  // Send message.
  $serviceBusRestProxy->sendQueueMessage("myqueue", $message);
} catch(ServiceException $e){
  $code = $e->getCode();
  $error_message = $e->getMessage();
  echo $code.": ".$error_message."<br />";
}
```

### Receive a Message

The primary way to receive messages from a queue is to use a **ServiceBusRestProxy->receiveQueueMessage** method. Messages can be received in two different modes: **ReceiveAndDelete** (mark message as consumed on read) and **PeekLock** (locks message for a period of time, but does not delete).

The example below demonstrates how a message can be received and processed using **PeekLock** mode (not the default mode).

```PHP
try {
  // Set the receive mode to PeekLock (default is ReceiveAndDelete).
  $options = new ReceiveMessageOptions();
  $options->setPeekLock(true);

  // Receive message.
  $message = $serviceBusRestProxy->receiveQueueMessage("myqueue", $options);
  echo "Body: ".$message->getBody()."<br />";
  echo "MessageID: ".$message->getMessageId()."<br />";

  // *** Process message here ***

  // Delete message.
  $serviceBusRestProxy->deleteMessage($message);
} catch(ServiceException $e){
  $code = $e->getCode();
  $error_message = $e->getMessage();
  echo $code.": ".$error_message."<br />";
}
```

## Service Bus Topics

### Create a Topic

```PHP
try {
  // Create topic.
  $topicInfo = new TopicInfo("mytopic");
  $serviceBusRestProxy->createTopic($topicInfo);
} catch(ServiceException $e){
  $code = $e->getCode();
  $error_message = $e->getMessage();
  echo $code.": ".$error_message."<br />";
}
```

### Create a subscription with the default (MatchAll) filter

```PHP
try {
  // Create subscription.
  $subscriptionInfo = new SubscriptionInfo("mysubscription");
  $serviceBusRestProxy->createSubscription("mytopic", $subscriptionInfo);
} catch(ServiceException $e){
  $code = $e->getCode();
  $error_message = $e->getMessage();
  echo $code.": ".$error_message."<br />";
}
```

### Send a message to a topic

Messages sent to Service Bus topics are instances of the **BrokeredMessage** class.

```PHP
try {
  // Create message.
  $message = new BrokeredMessage();
  $message->setBody("my message");

  // Send message.
  $serviceBusRestProxy->sendTopicMessage("mytopic", $message);
} catch(ServiceException $e){
  $code = $e->getCode();
  $error_message = $e->getMessage();
  echo $code.": ".$error_message."<br />";
}
```

### Receive a message from a topic

The primary way to receive messages from a subscription is to use a **ServiceBusRestProxy->receiveSubscriptionMessage** method. Received messages can work in two different modes: **ReceiveAndDelete** (the default) and **PeekLock** similarly to Service Bus Queues.

The example below demonstrates how a message can be received and processed using **ReceiveAndDelete** mode (the default mode).

```PHP
try {
  // Set receive mode to PeekLock (default is ReceiveAndDelete)
  $options = new ReceiveMessageOptions();
  $options->setReceiveAndDelete();

  // Get message.
  $message = $serviceBusRestProxy->receiveSubscriptionMessage("mytopic",
                                "mysubscription",
                                $options);
  echo "Body: ".$message->getBody()."<br />";
  echo "MessageID: ".$message->getMessageId()."<br />";
} catch(ServiceException $e){
  $code = $e->getCode();
  $error_message = $e->getMessage();
  echo $code.": ".$error_message."<br />";
}
```

# Need Help?

Raise an issue.

# Contribute Code or Provide Feedback

Information in setting up for development can be found at [develop.md](doc/develop.md)

If you encounter any bugs with the library please file an issue in the [Issues](https://github.com/baselsoftwaredev//issues) section of the project.

# Learn More
[Microsoft Azure Service Bus](https://docs.microsoft.com/en-us/azure/service-bus-messaging/service-bus-messaging-overview)
